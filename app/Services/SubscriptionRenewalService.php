<?php

namespace App\Services;

use App\Models\PaymentDetails;
use App\Models\Subscriber;
use App\Models\SubscriptionRenewal;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Razorpay\Api\Api;
use RuntimeException;

class SubscriptionRenewalService
{
    public const TIMEZONE = 'Asia/Kolkata';
    public const SUBSCRIPTION_DAYS = 28;
    public const BONUS_DAYS = 2;
    public const PENALTY_DAYS = 7;
    public const PENALTY_PER_DAY = 15;
    public const GST_PERCENT = 18;

    public function businessDate(?Carbon $now = null): Carbon
    {
        return ($now ?: Carbon::now(self::TIMEZONE))->setTimezone(self::TIMEZONE)->startOfDay();
    }

    public function quote(Subscriber $subscriber, ?Carbon $now = null): array
    {
        $today = $this->businessDate($now);
        $dueDate = $subscriber->expiryDate
            ? Carbon::parse($subscriber->expiryDate, self::TIMEZONE)->setTimezone(self::TIMEZONE)->startOfDay()
            : $today;

        $overdueDays = $today->greaterThan($dueDate) ? $dueDate->diffInDays($today) : 0;
        $penaltyDay = min(self::PENALTY_DAYS, $overdueDays);
        $penaltyPrincipal = $penaltyDay * self::PENALTY_PER_DAY;
        $price = $this->subscriptionPrice($subscriber);
        $subscriptionGst = $this->money($price * self::GST_PERCENT / 100);
        $penaltyGst = $this->money($penaltyPrincipal * self::GST_PERCENT / 100);
        $exactTotal = $this->money($price + $subscriptionGst + $penaltyPrincipal + $penaltyGst);
        $payable = (int) round($exactTotal, 0, PHP_ROUND_HALF_UP);
        $renewalDays = $overdueDays === 0 ? self::SUBSCRIPTION_DAYS + self::BONUS_DAYS : self::SUBSCRIPTION_DAYS;

        return [
            'cycle_key' => $subscriber->id . ':' . $dueDate->toDateString(),
            'due_date' => $dueDate->toDateString(),
            'business_date' => $today->toDateString(),
            'overdue_days' => $overdueDays,
            'penalty_day' => $penaltyDay,
            'subscription_days' => self::SUBSCRIPTION_DAYS,
            'bonus_days' => $overdueDays === 0 ? self::BONUS_DAYS : 0,
            'renewal_days' => $renewalDays,
            'subscription_price' => $price,
            'subscription_gst_percentage' => self::GST_PERCENT,
            'subscription_gst' => $subscriptionGst,
            'penalty_principal' => $this->money($penaltyPrincipal),
            'penalty_gst_percentage' => self::GST_PERCENT,
            'penalty_gst' => $penaltyGst,
            'exact_total_payable' => $exactTotal,
            'total_payable' => $this->money($payable),
            'total_payable_in_paise' => $payable * 100,
            'currency' => 'INR',
        ];
    }

    public function renewalFor(Subscriber $subscriber, array $quote): SubscriptionRenewal
    {
        $renewal = SubscriptionRenewal::firstOrNew(['cycle_key' => $quote['cycle_key']]);
        if (!$renewal->exists || in_array($renewal->status, ['quoted', 'failed'], true)) {
            $renewal->fill([
                'subscriber_id' => $subscriber->id,
                'due_date' => $quote['due_date'],
                'status' => 'quoted',
                'subscription_price' => $quote['subscription_price'],
                'subscription_gst' => $quote['subscription_gst'],
                'penalty_day' => $quote['penalty_day'],
                'penalty_principal' => $quote['penalty_principal'],
                'penalty_gst' => $quote['penalty_gst'],
                'renewal_days' => $quote['renewal_days'],
                'bonus_days' => $quote['bonus_days'],
                'total_payable' => $quote['total_payable'],
            ]);
            $renewal->save();
        }
        return $renewal;
    }

    public function createOrder(Subscriber $subscriber, string $idempotencyKey): array
    {
        $quote = $this->quote($subscriber);
        $renewal = $this->renewalFor($subscriber, $quote);

        if ($renewal->status === 'paid') {
            return ['renewal' => $renewal, 'quote' => $quote, 'already_paid' => true];
        }

        if ($renewal->razorpay_order_id) {
            return ['renewal' => $renewal, 'quote' => $quote, 'already_paid' => false];
        }

        $key = config('services.razorpay.key_id');
        $secret = config('services.razorpay.key_secret');
        if (!$key || !$secret) {
            throw new RuntimeException('Razorpay is not configured.');
        }

        $api = new Api($key, $secret);
        $order = $api->order->create([
            'receipt' => 'renewal-' . $renewal->id,
            'amount' => $quote['total_payable_in_paise'],
            'currency' => 'INR',
            'notes' => ['renewal_id' => (string) $renewal->id, 'idempotency_key' => $idempotencyKey],
        ]);

        $renewal->update([
            'idempotency_key' => $idempotencyKey,
            'razorpay_order_id' => $order->id,
            'status' => 'order_created',
        ]);

        return ['renewal' => $renewal->fresh(), 'quote' => $quote, 'already_paid' => false];
    }

    public function settle(Subscriber $subscriber, SubscriptionRenewal $renewal, string $paymentId, string $signature): array
    {
        $key = config('services.razorpay.key_id');
        $secret = config('services.razorpay.key_secret');
        if (!$key || !$secret || !$renewal->razorpay_order_id) {
            throw new RuntimeException('Renewal payment cannot be verified.');
        }

        $api = new Api($key, $secret);
        $api->utility->verifyPaymentSignature([
            'razorpay_order_id' => $renewal->razorpay_order_id,
            'razorpay_payment_id' => $paymentId,
            'razorpay_signature' => $signature,
        ]);
        $payment = $api->payment->fetch($paymentId);
        if ((string) ($payment->order_id ?? '') !== (string) $renewal->razorpay_order_id
            || (int) ($payment->amount ?? 0) !== (int) round((float) $renewal->total_payable * 100)
            || (string) ($payment->status ?? '') !== 'captured') {
            throw new RuntimeException('Razorpay payment does not match the renewal order.');
        }

        return DB::transaction(function () use ($subscriber, $renewal, $paymentId, $signature) {
            $lockedRenewal = SubscriptionRenewal::whereKey($renewal->id)->lockForUpdate()->firstOrFail();
            $lockedSubscriber = Subscriber::whereKey($subscriber->id)->lockForUpdate()->firstOrFail();

            if ($lockedRenewal->status === 'paid') {
                return ['renewal' => $lockedRenewal, 'already_paid' => true];
            }

            $existingPayment = PaymentDetails::where('payment_id', $paymentId)->first();
            if ($existingPayment) {
                throw new RuntimeException('Payment has already been processed.');
            }

            $payment = PaymentDetails::create([
                'subscriberId' => $lockedSubscriber->subscriberId,
                'payment_id' => $paymentId,
                'status_code' => '200',
                'amount' => (string) ($lockedRenewal->total_payable * 100),
                'signature' => $signature,
                'type' => 1,
            ]);

            $paymentDate = $this->businessDate();
            $expiry = $paymentDate->copy()->addDays((int) $lockedRenewal->renewal_days)->toDateString();
            $lockedSubscriber->update([
                'subscriptionDate' => $paymentDate->toDateString(),
                'expiryDate' => $expiry,
                'status' => 1,
                'activestatus' => 1,
                'notify' => 0,
                'need_to_pay' => 0,
            ]);

            $lockedRenewal->update([
                'payment_id' => $paymentId,
                'payment_details_id' => $payment->id,
                'payment_date' => $paymentDate->toDateString(),
                'paid_at' => now(),
                'status' => 'paid',
            ]);

            return ['renewal' => $lockedRenewal->fresh(), 'already_paid' => false];
        });
    }

    public function subscriptionPrice(Subscriber $subscriber): float
    {
        return is_numeric($subscriber->subscription_price) && (float) $subscriber->subscription_price > 0
            ? (float) $subscriber->subscription_price
            : 2.0;
    }

    private function money(float|int $amount): float
    {
        return round((float) $amount, 2, PHP_ROUND_HALF_UP);
    }
}

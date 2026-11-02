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
        $totalPayableInPaise = (int) round($exactTotal * 100);
        $renewalDays = $overdueDays === 0 ? self::SUBSCRIPTION_DAYS + self::BONUS_DAYS : self::SUBSCRIPTION_DAYS;
        $cycleKey = $this->resolveCycleKey($subscriber, $dueDate->toDateString(), $overdueDays);

        return [
            'cycle_key' => $cycleKey,
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
            'total_payable' => $exactTotal,
            'total_payable_in_paise' => $totalPayableInPaise,
            'currency' => 'INR',
        ];
    }

    public function resolveCycleKey(Subscriber $subscriber, string $dueDate, int $overdueDays = 0): string
    {
        $baseKey = ($subscriber->id ?: '0') . ':' . $dueDate;
        if (!$subscriber->exists) {
            return $baseKey;
        }

        $existing = SubscriptionRenewal::where('cycle_key', $baseKey)->first();

        // If no record exists yet, or the existing base record is not paid, use baseKey
        if (!$existing || $existing->status !== 'paid') {
            return $baseKey;
        }

        // If baseKey is paid, but subscriber is NOT expired (active in advance),
        // baseKey represents the current paid cycle
        if ($overdueDays === 0 && !empty($subscriber->expiryDate)) {
            try {
                $expiry = Carbon::parse($subscriber->expiryDate, self::TIMEZONE)->setTimezone(self::TIMEZONE)->endOfDay();
                if (!$expiry->isPast()) {
                    return $baseKey;
                }
            } catch (\Throwable $e) {
                // fall through to overdue handling
            }
        }

        // The base record is paid, but the subscriber is expired and needs a new renewal for this due date.
        // Check if an unpaid renewal already exists for this subscriber and due_date
        $unpaid = SubscriptionRenewal::where('subscriber_id', $subscriber->id)
            ->where('due_date', $dueDate)
            ->whereIn('status', ['quoted', 'order_created', 'failed'])
            ->orderBy('id', 'desc')
            ->first();

        if ($unpaid) {
            return $unpaid->cycle_key;
        }

        // Otherwise generate next deterministic key based on existing renewals count for this due_date
        $count = SubscriptionRenewal::where('subscriber_id', $subscriber->id)
            ->where('due_date', $dueDate)
            ->count();

        return $baseKey . ':' . ($count + 1);
    }

    public function renewalFor(Subscriber $subscriber, array $quote): SubscriptionRenewal
    {
        return DB::transaction(function () use ($subscriber, $quote) {
            $cycleKey = $quote['cycle_key'] ?? $this->resolveCycleKey(
                $subscriber,
                $quote['due_date'],
                (int) ($quote['overdue_days'] ?? 0)
            );

            $renewal = SubscriptionRenewal::where('cycle_key', $cycleKey)->lockForUpdate()->first();
            if (!$renewal) {
                $renewal = SubscriptionRenewal::create([
                    'subscriber_id' => $subscriber->id,
                    'cycle_key' => $cycleKey,
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
            } elseif (in_array($renewal->status, ['quoted', 'failed'], true)) {
                $renewal->update([
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
            }

            return $renewal;
        });
    }

    public function createOrder(Subscriber $subscriber, string $idempotencyKey): array
    {
        $quote = $this->quote($subscriber);
        $renewal = $this->renewalFor($subscriber, $quote);

        if ($renewal->status === 'paid' && $quote['overdue_days'] === 0) {
            return ['renewal' => $renewal, 'quote' => $quote, 'already_paid' => true];
        }

        $quoteAttributes = [
            'due_date' => $quote['due_date'],
            'subscription_price' => $quote['subscription_price'],
            'subscription_gst' => $quote['subscription_gst'],
            'penalty_day' => $quote['penalty_day'],
            'penalty_principal' => $quote['penalty_principal'],
            'penalty_gst' => $quote['penalty_gst'],
            'renewal_days' => $quote['renewal_days'],
            'bonus_days' => $quote['bonus_days'],
            'total_payable' => $quote['total_payable'],
        ];

        return DB::transaction(function () use ($subscriber, $renewal, $quote, $quoteAttributes, $idempotencyKey) {
            $lockedRenewal = SubscriptionRenewal::whereKey($renewal->id)->lockForUpdate()->firstOrFail();

            if ($lockedRenewal->status === 'paid' && $quote['overdue_days'] === 0) {
                return ['renewal' => $lockedRenewal, 'quote' => $quote, 'already_paid' => true];
            }

            $quoteIsUnchanged = true;
            foreach ($quoteAttributes as $attribute => $value) {
                $storedValue = $lockedRenewal->{$attribute};
                if ($attribute === 'due_date' && $storedValue) {
                    $matches = Carbon::parse($storedValue)->toDateString() === $value;
                } elseif (is_numeric($value) && is_numeric($storedValue)) {
                    $matches = round((float) $storedValue, 2) === round((float) $value, 2);
                } else {
                    $matches = (string) $storedValue === (string) $value;
                }
                if (!$matches) {
                    $quoteIsUnchanged = false;
                    break;
                }
            }

            if ($lockedRenewal->razorpay_order_id && $lockedRenewal->status === 'order_created' && $quoteIsUnchanged) {
                return ['renewal' => $lockedRenewal, 'quote' => $quote, 'already_paid' => false];
            }

            $key = config('services.razorpay.key_id');
            $secret = config('services.razorpay.key_secret');
            if (!$key || !$secret) {
                throw new RuntimeException('Razorpay is not configured.');
            }

            $api = $this->makeRazorpayApi($key, $secret);
            $order = $api->order->create([
                'receipt' => 'renewal-' . $lockedRenewal->id,
                'amount' => $quote['total_payable_in_paise'],
                'currency' => 'INR',
                'notes' => ['renewal_id' => (string) $lockedRenewal->id, 'idempotency_key' => $idempotencyKey],
            ]);

            $lockedRenewal->update(array_merge($quoteAttributes, [
                'idempotency_key' => $idempotencyKey,
                'razorpay_order_id' => $order->id,
                'status' => 'order_created',
            ]));

            return ['renewal' => $lockedRenewal->fresh(), 'quote' => $quote, 'already_paid' => false];
        });
    }

    public function settle(Subscriber $subscriber, SubscriptionRenewal $renewal, string $paymentId, string $signature): array
    {
        $key = config('services.razorpay.key_id');
        $secret = config('services.razorpay.key_secret');
        if (!$key || !$secret || !$renewal->razorpay_order_id) {
            throw new RuntimeException('Renewal payment cannot be verified.');
        }

        $api = $this->makeRazorpayApi($key, $secret);
        $api->utility->verifyPaymentSignature([
            'razorpay_order_id' => $renewal->razorpay_order_id,
            'razorpay_payment_id' => $paymentId,
            'razorpay_signature' => $signature,
        ]);
        $payment = $api->payment->fetch($paymentId);
        if ((string) ($payment->order_id ?? '') !== (string) $renewal->razorpay_order_id
            || (string) ($payment->status ?? '') !== 'captured') {
            throw new RuntimeException('Razorpay payment does not match the renewal order.');
        }

        $paymentDate = $this->businessDate();

        return DB::transaction(function () use ($subscriber, $renewal, $paymentId, $signature, $payment, $paymentDate) {
            $lockedRenewal = SubscriptionRenewal::whereKey($renewal->id)->lockForUpdate()->firstOrFail();
            $lockedSubscriber = Subscriber::whereKey($subscriber->id)->lockForUpdate()->firstOrFail();

            if ($lockedRenewal->status === 'paid') {
                return ['renewal' => $lockedRenewal, 'already_paid' => true];
            }

            $expectedOrderPaise = (int) round(((float) $lockedRenewal->total_payable) * 100);
            $paidAmount = (int) ($payment->amount ?? 0);

            // Verify payment amount strictly against original locked renewal order amount
            if ($paidAmount !== $expectedOrderPaise) {
                throw new RuntimeException('Razorpay payment does not match the renewal order amount.');
            }

            $existingPayment = PaymentDetails::where('payment_id', $paymentId)->first();
            if ($existingPayment) {
                throw new RuntimeException('Payment has already been processed.');
            }

            $paymentRecord = PaymentDetails::create([
                'subscriberId' => $lockedSubscriber->subscriberId,
                'payment_id' => $paymentId,
                'status_code' => '200',
                'amount' => (string) $paidAmount,
                'signature' => $signature,
                'type' => 1,
            ]);

            $renewalDays = (int) ($lockedRenewal->renewal_days ?: 28);
            $expiry = $paymentDate->copy()->addDays($renewalDays)->toDateString();
            $lockedSubscriber->update([
                'subscriptionDate' => $paymentDate->toDateString(),
                'expiryDate' => $expiry,
                'status' => 1,
                'activestatus' => 1,
                'notify' => 0,
                'need_to_pay' => 0,
            ]);

            $renewalUpdate = [
                'payment_id' => $paymentId,
                'payment_details_id' => $paymentRecord->id,
                'payment_date' => $paymentDate->toDateString(),
                'paid_at' => now(),
                'status' => 'paid',
            ];

            $lockedRenewal->update($renewalUpdate);

            return ['renewal' => $lockedRenewal->fresh(), 'already_paid' => false];
        });
    }

    public function subscriptionPrice(Subscriber $subscriber): float
    {
        return is_numeric($subscriber->subscription_price) && (float) $subscriber->subscription_price > 0
            ? (float) $subscriber->subscription_price
            : 2.0;
    }

    protected function makeRazorpayApi(string $key, string $secret)
    {
        return new Api($key, $secret);
    }

    private function money(float|int $amount): float
    {
        return round((float) $amount, 2, PHP_ROUND_HALF_UP);
    }
}

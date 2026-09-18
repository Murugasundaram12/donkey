<?php

namespace App\Console\Commands;

use App\Models\Subscriber;
use App\Services\SubscriptionRenewalService;
use App\Services\VendorNotificationService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeactivateExpiredSubscribers extends Command
{
    protected $signature = 'subscriptions:deactivate-expired';

    protected $description = 'Deactivate subscribers whose subscription expired without renewal';

    public function handle(): int
    {
        $service = app(SubscriptionRenewalService::class);
        $businessDate = $service->businessDate();
        $deactivated = 0;

        Subscriber::whereNotNull('expiryDate')->chunkById(100, function ($vendors) use (&$deactivated, $businessDate, $service) {
            foreach ($vendors as $vendor) {
                $dueDate = Carbon::parse($vendor->expiryDate, SubscriptionRenewalService::TIMEZONE)->setTimezone(SubscriptionRenewalService::TIMEZONE)->startOfDay();
                if ($businessDate->lte($dueDate->copy()->addDays(SubscriptionRenewalService::PENALTY_DAYS))) {
                    continue;
                }
                if ((int) ($vendor->blockedstatus ?? 1) === 0 || ((int) ($vendor->status ?? 1) === 0 && (int) ($vendor->activestatus ?? 1) === 0)) {
                    continue;
                }

                $changed = Subscriber::whereKey($vendor->id)
                    ->where(function ($query) {
                        $query->where('status', '!=', 0)->orWhereNull('status')->orWhere('activestatus', '!=', 0)->orWhereNull('activestatus');
                    })
                    ->update(['status' => 0, 'activestatus' => 0, 'need_to_pay' => 1]);

                if (!$changed) {
                    continue;
                }
                $deactivated++;
                $renewal = $service->renewalFor($vendor, $service->quote($vendor, $businessDate));
                if (is_null($renewal->deactivated_notified_at) && $renewal->newQuery()->whereKey($renewal->id)->whereNull('deactivated_notified_at')->update(['deactivated_notified_at' => now()])) {
                    app(VendorNotificationService::class)->create($vendor, 'Payments', 'Subscription Deactivated', 'Your subscription has been deactivated due to non-payment. Please contact Admin to renew your subscription and reactivate your account.', ['event' => 'subscription_deactivated', 'cycle_key' => $renewal->cycle_key]);
                }
            }
        });

        $this->info("Deactivated {$deactivated} expired subscriber(s).");

        return self::SUCCESS;
    }

    protected function expiredActiveSubscribersQuery(string $businessDate)
    {
        return Subscriber::query()
            ->whereNotNull('expiryDate')
            ->whereDate('expiryDate', '<', $businessDate)
            ->whereDate('expiryDate', '<', Carbon::parse($businessDate)->subDays(SubscriptionRenewalService::PENALTY_DAYS)->toDateString())
            ->where(function ($query) {
                $query->where('status', '!=', 0)
                    ->orWhereNull('status')
                    ->orWhere('activestatus', '!=', 0)
                    ->orWhereNull('activestatus');
            });
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Subscriber;
use App\Services\SubscriptionRenewalService;
use App\Services\VendorNotificationService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CreateVendorPaymentReminders extends Command
{
    protected $signature = 'notifications:payment-reminders';
    protected $description = 'Create subscription-expiry reminders for vendors';

    public function handle(): int
    {
        $created = 0;
        $today = app(SubscriptionRenewalService::class)->businessDate();
        Subscriber::whereNotNull('expiryDate')->chunkById(100, function ($vendors) use (&$created, $today) {
            foreach ($vendors as $vendor) {
                $dueDate = Carbon::parse($vendor->expiryDate, SubscriptionRenewalService::TIMEZONE)->setTimezone(SubscriptionRenewalService::TIMEZONE)->startOfDay();
                $days = $today->diffInDays($dueDate, false);
                $renewal = app(SubscriptionRenewalService::class)->renewalFor($vendor, app(SubscriptionRenewalService::class)->quote($vendor, $today));

                if ($days === 2 && is_null($renewal->before_due_notified_at) && $renewal->newQuery()->whereKey($renewal->id)->whereNull('before_due_notified_at')->update(['before_due_notified_at' => now()])) {
                    app(VendorNotificationService::class)->create($vendor, 'Payments', 'Subscription Payment Reminder', 'Your subscription payment is due on ' . $dueDate->format('d/m/Y') . '. Please complete the payment before the due date.', ['event' => 'subscription_before_due', 'cycle_key' => $renewal->cycle_key]);
                    $created++;
                }

                if ($days === 0 && is_null($renewal->due_notified_at) && $renewal->newQuery()->whereKey($renewal->id)->whereNull('due_notified_at')->update(['due_notified_at' => now()])) {
                    app(VendorNotificationService::class)->create($vendor, 'Payments', 'Subscription Payment Due Today', 'Today is your subscription payment due date. Please complete the payment to continue your subscription.', ['event' => 'subscription_due_today', 'cycle_key' => $renewal->cycle_key]);
                    $created++;
                }
            }
        });

        $this->info("Created {$created} vendor payment reminder(s).");
        return self::SUCCESS;
    }
}

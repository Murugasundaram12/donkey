<?php

namespace App\Console\Commands;

use App\Models\Pushnotification;
use App\Models\Subscriber;
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
        Subscriber::whereNotNull('expiryDate')->chunkById(100, function ($vendors) use (&$created) {
            foreach ($vendors as $vendor) {
                $days = Carbon::now()->startOfDay()->diffInDays(Carbon::parse($vendor->expiryDate)->startOfDay(), false);
                if ($days !== 15) {
                    continue;
                }

                $alreadyCreated = Pushnotification::where('subscriber_id', $vendor->id)
                    ->where('category', 'Payments')
                    ->where('title', 'Payment Reminder')
                    ->whereDate('created_at', today())
                    ->exists();

                if (!$alreadyCreated) {
                    app(VendorNotificationService::class)->create(
                        $vendor,
                        'Payments',
                        'Payment Reminder',
                        'Your subscription will expire in 15 days.',
                        ['event' => 'payment_reminder', 'days_remaining' => 15]
                    );
                    $created++;
                }
            }
        });

        $this->info("Created {$created} vendor payment reminder(s).");
        return self::SUCCESS;
    }
}

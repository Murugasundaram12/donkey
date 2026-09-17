<?php

namespace App\Console\Commands;

use App\Models\Subscriber;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeactivateExpiredSubscribers extends Command
{
    protected $signature = 'subscriptions:deactivate-expired';

    protected $description = 'Deactivate subscribers whose subscription expired without renewal';

    public function handle(): int
    {
        $businessDate = Carbon::now(config('app.timezone'))->toDateString();
        $deactivated = $this->expiredActiveSubscribersQuery($businessDate)->update([
            'status' => 0,
            'activestatus' => 0,
        ]);

        $this->info("Deactivated {$deactivated} expired subscriber(s).");

        return self::SUCCESS;
    }

    protected function expiredActiveSubscribersQuery(string $businessDate)
    {
        return Subscriber::query()
            ->whereNotNull('expiryDate')
            ->whereDate('expiryDate', '<', $businessDate)
            ->where(function ($query) {
                $query->where('status', '!=', 0)
                    ->orWhereNull('status')
                    ->orWhere('activestatus', '!=', 0)
                    ->orWhereNull('activestatus');
            });
    }
}

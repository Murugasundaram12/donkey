<?php

namespace App\Console\Commands;

use App\Models\Subscriber;
use Illuminate\Console\Command;
use Carbon\Carbon;

class RemoveInactiveUserPincode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove:pincode';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove pincode from subscribers with no recharge in last 7 days or null last_recharge_date';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $sevenDaysAgo = Carbon::now()->subDays(7);

        $updated = Subscriber::where(function ($query) use ($sevenDaysAgo) {
            $query->whereNull('last_recharge_date')
                ->orWhere('last_recharge_date', '<', $sevenDaysAgo);
        })
            ->whereNotNull('pincode')
            ->update(['pincode' => null]);

        $this->info("Removed pincode from {$updated} inactive subscribers.");

        return 0;
    }
}

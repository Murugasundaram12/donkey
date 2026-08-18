<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RestoreUsersFromRecUsersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:restore-from-rec-users {--dry-run : Perform a read-only analysis without making any database changes} {--force : Execute actual data restoration}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Safely reconcile and restore missing user columns and records from rec_users to live users table with explicit conflict handling';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run') || !$this->option('force');

        $this->info("=== RESTORE USERS FROM REC_USERS (" . ($isDryRun ? "DRY-RUN / READ-ONLY MODE" : "EXECUTION MODE") . ") ===");

        // 1. Verify table existence
        if (!Schema::hasTable('users') || !Schema::hasTable('rec_users')) {
            $this->error("Error: Both 'users' and 'rec_users' tables must exist.");
            return 1;
        }

        $recTotal = DB::table('rec_users')->count();
        $liveTotal = DB::table('users')->count();

        $matchingIds = DB::table('users')->join('rec_users', 'users.id', '=', 'rec_users.id')->count();
        $missingUserIds = DB::table('rec_users')->whereNotIn('id', DB::table('users')->pluck('id'))->pluck('id')->toArray();

        // 2. Email Conflicts
        $dupEmails = DB::table('rec_users')
            ->select('email', DB::raw('COUNT(*) as cnt'))
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->groupBy('email')
            ->having('cnt', '>', 1)
            ->pluck('email');

        // 3. Duplicate Phones
        $dupPhones = DB::table('rec_users')
            ->select('phone', DB::raw('COUNT(*) as cnt'))
            ->whereNotNull('phone')
            ->where('phone', '!=', '')
            ->groupBy('phone')
            ->having('cnt', '>', 1)
            ->get();

        // 4. Missing Users Classification
        $safeInserts = [];
        $skippedInserts = [];

        foreach ($missingUserIds as $mid) {
            $r = DB::table('rec_users')->where('id', $mid)->first();
            $emailConflict = false;
            if ($r->email && $r->email !== '') {
                $emailConflict = DB::table('users')->where('email', $r->email)->exists();
            }

            if ($emailConflict) {
                $skippedInserts[] = $r;
            } else {
                $safeInserts[] = $r;
            }
        }

        $this->info("\n=== USERS RESTORE DRY RUN SUMMARY ===");
        $this->table(['Metric', 'Count'], [
            ['Existing users in live table', $liveTotal],
            ['Total rec_users records', $recTotal],
            ['Matching IDs to update', $matchingIds],
            ['Missing users in live table', count($missingUserIds)],
            ['Email conflict groups in rec_users', count($dupEmails)],
            ['Duplicate phone groups in rec_users', count($dupPhones)],
            ['Safe inserts (no email conflict)', count($safeInserts)],
            ['Skipped inserts (email conflict)', count($skippedInserts)],
        ]);

        // Detailed Email Conflicts Table
        $this->info("\n--- 1. EMAIL CONFLICTS IN REC_USERS (" . count($dupEmails) . " Groups) ---");
        $emailConflictRows = [];
        foreach ($dupEmails as $e) {
            $recRows = DB::table('rec_users')->where('email', $e)->get();
            foreach ($recRows as $r) {
                $liveMatch = DB::table('users')->where('id', $r->id)->first();
                $emailConflictRows[] = [
                    'rec_id' => $r->id,
                    'user_id' => $r->user_id,
                    'name' => $r->name,
                    'email' => $r->email,
                    'phone' => $r->phone,
                    'live_match' => $liveMatch ? "ID {$liveMatch->id} (Present)" : "Missing from live users",
                    'action' => $liveMatch ? "Update Application Columns" : "SKIP (Email Conflict)"
                ];
            }
        }
        $this->table(['rec_id', 'user_id', 'name', 'email', 'phone', 'live_match', 'action'], $emailConflictRows);

        // Detailed Duplicate Phones Table
        $this->info("\n--- 2. DUPLICATE PHONE GROUPS IN REC_USERS (" . count($dupPhones) . " Groups) ---");
        $phoneRows = [];
        foreach ($dupPhones as $p) {
            $recRows = DB::table('rec_users')->where('phone', $p->phone)->get();
            foreach ($recRows as $r) {
                $phoneRows[] = [
                    'phone' => $p->phone,
                    'count' => $p->cnt,
                    'id' => $r->id,
                    'user_id' => $r->user_id,
                    'name' => $r->name,
                    'email' => $r->email
                ];
            }
        }
        $this->table(['phone', 'count', 'id', 'user_id', 'name', 'email'], $phoneRows);

        // Detailed 11 Missing Users Table
        $this->info("\n--- 3. MISSING USERS AUDIT (11 Records) ---");
        $missingRows = [];
        foreach ($missingUserIds as $mid) {
            $r = DB::table('rec_users')->where('id', $mid)->first();
            $emailConflict = ($r->email && $r->email !== '') ? DB::table('users')->where('email', $r->email)->exists() : false;
            $missingRows[] = [
                'id' => $r->id,
                'user_id' => $r->user_id,
                'name' => $r->name,
                'email' => $r->email,
                'phone' => $r->phone,
                'created_at' => $r->created_at,
                'action' => $emailConflict ? "SKIP (Email Conflict)" : "SAFE TO INSERT"
            ];
        }
        $this->table(['id', 'user_id', 'name', 'email', 'phone', 'created_at', 'action'], $missingRows);

        if ($isDryRun) {
            $this->warn("\n[DRY-RUN COMPLETE]: 0 database modifications were executed. Run with '--force' to execute restoration.");
            return 0;
        }

        // Execution mode
        $this->info("\nStarting transaction for production data restoration...");

        try {
            $updatedCount = 0;
            $insertedCount = 0;

            DB::transaction(function () use (&$updatedCount, &$insertedCount, $safeInserts) {
                // Step 1: Single set-based UPDATE for all 3,439 matching records
                $updatedCount = DB::affectingStatement("
                    UPDATE `users` u
                    JOIN `rec_users` ru ON u.id = ru.id
                    SET 
                      u.user_id = ru.user_id,
                      u.is_googleUser = ru.is_googleUser,
                      u.firstname = ru.firstname,
                      u.lastname = ru.lastname,
                      u.city = ru.city,
                      u.country_code = ru.country_code,
                      u.phone = ru.phone,
                      u.phone_code = ru.phone_code,
                      u.otp = ru.otp,
                      u.plant_code = ru.plant_code,
                      u.is_live = ru.is_live,
                      u.is_driver = ru.is_driver,
                      u.department = ru.department,
                      u.profile_image = ru.profile_image,
                      u.file_data = ru.file_data,
                      u.last_login = ru.last_login,
                      u.roles = ru.roles,
                      u.email_verified = ru.email_verified,
                      u.phone_verified = ru.phone_verified,
                      u.phone_verified_at = ru.phone_verified_at,
                      u.user_timezone = ru.user_timezone,
                      u.dob = ru.dob,
                      u.gender = ru.gender,
                      u.image = ru.image,
                      u.device_token = ru.device_token,
                      u.logout_time = ru.logout_time,
                      u.blockedstatus = ru.blockedstatus,
                      u.coins = ru.coins,
                      u.referred_by = ru.referred_by,
                      u.referral_code = ru.referral_code
                ");

                // Step 2: Insert safe missing records (2 safe records: IDs 3701 and 3702)
                foreach ($safeInserts as $rec) {
                    DB::table('users')->insert([
                        'id' => $rec->id,
                        'user_id' => $rec->user_id,
                        'is_googleUser' => $rec->is_googleUser,
                        'firstname' => $rec->firstname,
                        'lastname' => $rec->lastname,
                        'name' => $rec->name ?: 'User ' . $rec->id,
                        'email' => $rec->email ?: ('user_' . $rec->id . '@donkeydeliveries.com'),
                        'password' => $rec->password ?: bcrypt('123456'),
                        'city' => $rec->city,
                        'country_code' => $rec->country_code,
                        'phone' => $rec->phone,
                        'phone_code' => $rec->phone_code,
                        'otp' => $rec->otp,
                        'plant_code' => $rec->plant_code,
                        'is_live' => $rec->is_live,
                        'is_driver' => $rec->is_driver,
                        'department' => $rec->department,
                        'profile_image' => $rec->profile_image,
                        'file_data' => $rec->file_data,
                        'last_login' => $rec->last_login,
                        'roles' => $rec->roles,
                        'email_verified' => $rec->email_verified,
                        'email_verified_at' => $rec->email_verified_at,
                        'phone_verified' => $rec->phone_verified,
                        'phone_verified_at' => $rec->phone_verified_at,
                        'remember_token' => $rec->remember_token,
                        'user_timezone' => $rec->user_timezone,
                        'dob' => $rec->dob,
                        'gender' => $rec->gender,
                        'image' => $rec->image,
                        'device_token' => $rec->device_token,
                        'logout_time' => $rec->logout_time,
                        'blockedstatus' => $rec->blockedstatus,
                        'coins' => $rec->coins,
                        'referred_by' => $rec->referred_by,
                        'referral_code' => $rec->referral_code,
                        'created_at' => $rec->created_at ?: date('Y-m-d H:i:s'),
                        'updated_at' => $rec->updated_at ?: date('Y-m-d H:i:s'),
                    ]);
                    $insertedCount++;
                }

                // Step 3: Resync AUTO_INCREMENT
                $maxId = DB::table('users')->max('id') ?: 1;
                DB::statement("ALTER TABLE `users` AUTO_INCREMENT = " . ($maxId + 1));
            });

            $this->info("SUCCESS: Production users table data restored cleanly.");
            $this->info("  - Records Updated: {$updatedCount}");
            $this->info("  - Records Inserted: {$insertedCount}");
            $this->info("  - Records Skipped (Email Conflict): " . count($skippedInserts));
            return 0;
        } catch (\Throwable $e) {
            $this->error("ERROR during restoration transaction: " . $e->getMessage());
            return 1;
        }
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('companies', 'company_id')) {
            // Add column as nullable first
            Schema::table('companies', function (Blueprint $table) {
                $table->string('company_id', 8)->nullable()->after('id');
            });

            // Backfill company_id for existing records
            $companies = DB::table('companies')->whereNull('company_id')->get();
            foreach ($companies as $company) {
                $companyId = $this->generateUniqueCompanyId();
                DB::table('companies')
                    ->where('id', $company->id)
                    ->update(['company_id' => $companyId]);
            }

            // Make non-nullable and add unique index using raw SQL
            DB::statement('ALTER TABLE companies MODIFY company_id VARCHAR(8) NOT NULL');
            DB::statement('ALTER TABLE companies ADD UNIQUE INDEX companies_company_id_unique (company_id)');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('companies', 'company_id')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->dropUnique('companies_company_id_unique');
                $table->dropColumn('company_id');
            });
        }
    }

    private function generateUniqueCompanyId(): string
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        do {
            $companyId = strtoupper(substr(str_shuffle($chars), 0, 8));
            $exists = DB::table('companies')->where('company_id', $companyId)->exists();
        } while ($exists);

        return $companyId;
    }
};

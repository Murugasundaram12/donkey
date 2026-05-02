<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Company;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            if (!Schema::hasColumn('companies', 'api_key')) {
                $table->string('api_key', 64)->nullable()->unique()->after('status');
            }
        });

        // Backfill API keys for existing companies
        $companies = DB::table('companies')->whereNull('api_key')->get();
        foreach ($companies as $company) {
            DB::table('companies')
                ->where('id', $company->id)
                ->update(['api_key' => $this->generateApiKey()]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            if (Schema::hasColumn('companies', 'api_key')) {
                $table->dropUnique(['api_key']);
                $table->dropColumn('api_key');
            }
        });
    }

    private function generateApiKey(): string
    {
        return hash('sha256', uniqid('dk_live_', true) . random_bytes(32));
    }
};

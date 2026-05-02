<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add columns WITHOUT unique first
        Schema::table('companies', function (Blueprint $table) {
            $table->string('company_id', 8)->nullable()->after('id');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
        });

        // 2. Backfill company_id safely
        $companies = DB::table('companies')->get();

        foreach ($companies as $company) {
            DB::table('companies')
                ->where('id', $company->id)
                ->update([
                    'company_id' => $this->generateUniqueCompanyId()
                ]);
        }

        // 3. Add UNIQUE constraint AFTER filling data
        Schema::table('companies', function (Blueprint $table) {
            $table->string('company_id', 8)->nullable(false)->change();
            $table->unique('company_id');
        });

        // 4. Rename column (optional – only if needed)
        if (Schema::hasColumn('companies', 'company_name')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->renameColumn('company_name', 'name');
            });
        }
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropUnique(['company_id']);
            $table->dropColumn(['company_id', 'email', 'phone', 'address']);
        });

        if (Schema::hasColumn('companies', 'name')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->renameColumn('name', 'company_name');
            });
        }
    }

    private function generateUniqueCompanyId(): string
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        do {
            $companyId = substr(str_shuffle($chars), 0, 8);
        } while (
            DB::table('companies')->where('company_id', $companyId)->exists()
        );

        return $companyId;
    }
};

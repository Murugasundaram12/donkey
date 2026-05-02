<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            if (!Schema::hasColumn('companies', 'gst_number')) {
                $table->string('gst_number')->nullable()->after('address');
            }
            if (!Schema::hasColumn('companies', 'website')) {
                $table->string('website')->nullable()->after('gst_number');
            }
            if (!Schema::hasColumn('companies', 'logo')) {
                $table->string('logo')->nullable()->after('website');
            }
            if (!Schema::hasColumn('companies', 'status')) {
                $table->enum('status', ['active', 'inactive'])->default('active')->after('logo');
            }
            if (!Schema::hasColumn('companies', 'contact_person')) {
                $table->string('contact_person')->nullable()->after('status');
            }
            if (!Schema::hasColumn('companies', 'contact_person_phone')) {
                $table->string('contact_person_phone')->nullable()->after('contact_person');
            }
            if (!Schema::hasColumn('companies', 'city')) {
                $table->string('city')->nullable()->after('address');
            }
            if (!Schema::hasColumn('companies', 'state')) {
                $table->string('state')->nullable()->after('city');
            }
            if (!Schema::hasColumn('companies', 'country')) {
                $table->string('country')->nullable()->after('state');
            }
            if (!Schema::hasColumn('companies', 'pincode')) {
                $table->string('pincode')->nullable()->after('country');
            }
            if (!Schema::hasColumn('companies', 'deleted_at')) {
                $table->softDeletes()->after('updated_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $columns = [
                'gst_number',
                'website',
                'logo',
                'status',
                'contact_person',
                'contact_person_phone',
                'city',
                'state',
                'country',
                'pincode',
                'deleted_at'
            ];
            foreach ($columns as $column) {
                if (Schema::hasColumn('companies', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};

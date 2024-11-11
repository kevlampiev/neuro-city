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
        Schema::table('bank_accounts', function (Blueprint $table) {
            $table->string('adesk_id')->nullable()->after('description');
        });

        Schema::table('cfs_items', function (Blueprint $table) {
            $table->string('adesk_id')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bank_accounts', function (Blueprint $table) {
            $table->dropColumn('adesk_id');
        });

        Schema::table('cfs_items', function (Blueprint $table) {
            $table->dropColumn('adesk_id');
        });
    }
};

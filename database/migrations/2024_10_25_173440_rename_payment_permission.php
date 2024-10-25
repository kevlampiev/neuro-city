<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("UPDATE permissions set slug='s-payments' WHERE slug='s-real_payment'");
        DB::statement("UPDATE permissions set slug='e-payments' WHERE slug='e-real_payment'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("UPDATE permissions set slug='s-real_payment' WHERE slug='s-payments'");
        DB::statement("UPDATE permissions set slug='e-real_payment' WHERE slug='e-payments'");
    }
};

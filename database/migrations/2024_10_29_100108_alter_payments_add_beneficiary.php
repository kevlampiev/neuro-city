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
        Schema::table('payments', function (Blueprint $table) {
            $table->unsignedBigInteger('beneficiary_id')->nullable()->comment('В пользу кого произошел платеж (если оплата идет по письму). Если поле null то считаем, что платеж идет в пользу владельца счета');
            $table->foreign('beneficiary_id')->on('companies')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['beneficiary_id']);
            $table->dropColumn('beneficiary_id');
        });
    }
};

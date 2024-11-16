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
        Schema::table('import_adesk_operations', function(Blueprint $table)
        {
            $table->boolean('has_accrual')->default(false);
            $table->date('accrual_date_open')->nullable();
            // $table->unsignedBigInteger('agreement_id'); Возможно должэен быть свой договор для accrual, но вряд ли
            $table->unsignedBigInteger('pl_item_id')->nullable(); 
            // $table->unsignedBigInteger('project_id')->nullable(); 
            // $table->float('amount');
            $table->string('accrual_description')->nullable();

            $table->foreign('pl_item_id')->on('pl_items')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('import_adesk_operations', function(Blueprint $table)
        {
            $table->dropForeign(['pl_item_id']);
            $table->dropColumn('has_accrual');
            $table->dropColumn('accrual_date_open');
            $table->dropColumn('pl_item_id'); 
            $table->dropColumn('accrual_description')->nullable();
        });
    }
};

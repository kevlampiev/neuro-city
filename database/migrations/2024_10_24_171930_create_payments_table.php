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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->date('date_open')->nullable(false);
            $table->unsignedBigInteger('bank_account_id')->nullable(false);
            $table->unsignedBigInteger('agreement_id')->nullable(false);
            $table->float('amount')->default(0);
            $table->float('VAT')->default(0)->comment('Сумма НДС');
            $table->string('description')->nullable(false);
            $table->unsignedBigInteger('project_id')->nullable(true);
            $table->unsignedBigInteger('cfs_item_id')->nullable(false);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('bank_account_id')->on('bank_accounts')->references('id');
            $table->foreign('agreement_id')->on('agreements')->references('id');
            $table->foreign('project_id')->on('projects')->references('id');
            $table->foreign('cfs_item_id')->on('cfs_items')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

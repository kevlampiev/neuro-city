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
        Schema::create('plan_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agreement_id');
            $table->unsignedBigInteger('project_id')->nullable();
            $table->date('initial_date')->comment('Первоначальная дата по договору');
            $table->date('shifted_date')->comment('Планируемая дата по договору');
            $table->float('amount');
            $table->float('VAT');
            $table->unsignedBigInteger('cfs_item_id');
            $table->string('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by');

            $table->foreign('agreement_id')->references('id')->on('agreements');
            $table->foreign('project_id')->references('id')->on('projects');
            $table->foreign('cfs_item_id')->references('id')->on('cfs_items');
            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_payments');
    }
};

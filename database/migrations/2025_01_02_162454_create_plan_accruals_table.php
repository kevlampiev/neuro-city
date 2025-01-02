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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->boolean('is_service')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });    

        Schema::create('plan_accruals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agreement_id');
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('product_id')->comment('поставяемый продукт/оказываемая услуга');
            $table->date('initial_date')->comment('Первоначальная дата поставки по договору');
            $table->date('shifted_date')->comment('Планируемая дата поставки по договору');
            $table->float('amount')->comment('стоимость продукта/услуги по договору без НДС');
            $table->unsignedBigInteger('pl_item_id');
            $table->string('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by');

            $table->foreign('agreement_id')->references('id')->on('agreements');
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('project_id')->references('id')->on('projects');
            $table->foreign('pl_item_id')->references('id')->on('pl_items');
            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_accruals');
        Schema::dropIfExists('products');
    }
};

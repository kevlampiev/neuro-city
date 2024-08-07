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
        Schema::create('agreements', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('название договора')->default('Договор');
            $table->string('agr_number')->default('б.н.');
            $table->date('date_open')->useCurrent()->comment('Дата договора');
            $table->date('date_close')->comment('Номинальная дата закрытия');
            $table->unsignedBigInteger('agreement_type_id')->comment('Тип договора');
            $table->unsignedBigInteger('seller_id')->comment('Поставщик услуг');
            $table->unsignedBigInteger('buyer_id')->comment('Покупатель услуг');
            $table->text('description')->nullable();
            $table->date('real_date_close')->nullable(true)->comment('Фактическая дата закрытия');
            $table->timestamps();
            $table->unsignedBigInteger('created_by');
            $table->softDeletes();
            $table->unsignedBigInteger('droped_by')->nullable();
            $table->foreign('agreement_type_id')->on('agreement_types')->references('id');
            $table->foreign('seller_id')->on('companies')->references('id');
            $table->foreign('buyer_id')->on('companies')->references('id');
            $table->foreign('created_by')->on('users')->references('id');
            $table->foreign('droped_by')->on('users')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agreements');
    }
};

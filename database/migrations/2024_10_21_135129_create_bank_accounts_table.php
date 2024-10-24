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
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_number', 20)->nullable(false);
            $table->unsignedBigInteger('owner_id')->nullable(false);
            $table->unsignedBigInteger('bank_id')->nullable(false);
            $table->date('date_open');
            $table->date('date_close')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('owner_id')->on('companies')->references('id');
            $table->foreign('bank_id')->on('companies')->references('id');
            $table->foreign('created_by')->on('users')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_accounts');
    }
};

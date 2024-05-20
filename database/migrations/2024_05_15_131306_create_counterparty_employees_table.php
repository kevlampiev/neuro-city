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
        Schema::create('company_employees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable(false);
            $table->string('name')->nullable(false);
            $table->string('title')->nullable(true)->comment('Должность в компании');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->date('birthday')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('user_id')->nullable(false)->comment('Кто описал данного сотрудника');
            $table->foreign('company_id')->on('companies')->references('id');
            $table->foreign('user_id')->on('users')->references('id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_employees');
    }
};

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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name')->required();
            $table->string('fullname')->nullable();
            $table->string('inn',15)->nullable(false)->unique();
            $table->string('ogrn',20)->nullable();
            $table->date('established_date')->nullable();
            $table->string('header')->nullable();
            $table->string('phone')->nullable();
            $table->enum('company_type',['lessor', 'bank', 'insurer', 'government', 'other'])->default('other');
            $table->boolean('our_company')->default(false);
            $table->string('post_adress')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};

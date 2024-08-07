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
        Schema::create('agreement_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->enum('segment',['operations', 'finance', 'investitions'])->default('operations');
            $table->boolean('system')->default(false)->comment('системные типы невозможно изменить или удалить даже администратору');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agreement_types');
    }
};

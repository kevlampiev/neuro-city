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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->require()->comment('Автор сообщения');
            $table->unsignedBigInteger('task_id')->require()->comment('Задача к которой относится сообщение');
            $table->string('description')->nullable(true);
            $table->unsignedBigInteger('reply_to_message_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('user_id')->on('users')->references('id');
            $table->foreign('task_id')->on('tasks')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};

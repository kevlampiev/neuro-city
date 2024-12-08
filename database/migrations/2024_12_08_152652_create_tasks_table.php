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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(false)->comment('постановщик задачи');
            $table->unsignedBigInteger('task_performer_id')->nullable(false)
                ->comment('исполнитель задачи');
            $table->dateTime('start_date')->nullable(false)->comment('дата начала');
            $table->dateTime('due_date')->nullable(false)
                ->comment('плановая дата окончания');
            $table->dateTime('terminate_date')->nullable(true)
                ->comment('дата реального окончания');
            $table->enum('terminate_status', ['complete', 'cancel'])
                ->nullable(true)->comment('статус закрытия');
            $table->string('subject')->nullable(false)
                ->comment('название задачи');
            $table->enum('importance', ['low', 'medium', 'high'])->default('medium');    
            $table->text('description')->nullable();
            $table->unsignedBigInteger('parent_task_id')->nullable();
            $table->boolean('hidden_task')->default(false)->comment('Если поле отмечено, задача не отражается в списках Мои задачи');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};

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
        Schema::table('agreements', function (Blueprint $table) {
            // Добавляем столбец project_id для связи с таблицей projects
            $table->unsignedBigInteger('project_id')->nullable()->after('id');
            // Создаем внешний ключ, связывающий project_id с таблицей projects
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agreements', function (Blueprint $table) {
        // Удаляем внешний ключ и столбец project_id при откате миграции
        $table->dropForeign(['project_id']);
        $table->dropColumn('project_id');
        });
    }
};

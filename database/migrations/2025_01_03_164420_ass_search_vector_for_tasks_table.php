<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        // Добавляем поле tsvector через сырой SQL
        DB::statement("ALTER TABLE tasks ADD COLUMN search_vector tsvector");

        // Выполняем транзакцию для создания индекса и триггера
        DB::transaction(function () {
            // Обновляем поле search_vector для существующих данных с использованием конфигурации русского языка
            DB::statement("
                UPDATE tasks
                SET search_vector = to_tsvector('russian', coalesce(subject, '') || ' ' || coalesce(description, ''))
            ");

            // Создаем функцию для автоматического обновления search_vector
            DB::unprepared("
                CREATE OR REPLACE FUNCTION update_task_search_vector() RETURNS trigger AS $$
                BEGIN
                    NEW.search_vector := to_tsvector('russian', coalesce(NEW.subject, '') || ' ' || coalesce(NEW.description, ''));
                    RETURN NEW;
                END;
                $$ LANGUAGE plpgsql;

                CREATE TRIGGER tsvectorupdate BEFORE INSERT OR UPDATE
                ON tasks FOR EACH ROW EXECUTE PROCEDURE update_task_search_vector();
            ");

            // Создаем GIN-индекс для поля search_vector
            DB::statement("
                CREATE INDEX tasks_search_idx ON tasks USING GIN(search_vector);
            ");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Удаляем триггер, индекс и колонку search_vector при откате миграции
        DB::transaction(function () {
            DB::unprepared("DROP TRIGGER IF EXISTS tsvectorupdate ON tasks");
            DB::statement("DROP INDEX IF EXISTS tasks_search_idx");
        });

        DB::statement("ALTER TABLE tasks DROP COLUMN search_vector");
    }
};

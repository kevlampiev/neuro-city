<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddSearchVectorToProjectsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Добавляем поле tsvector через сырой SQL
        DB::statement("ALTER TABLE projects ADD COLUMN search_vector tsvector");

        // Выполняем транзакцию для создания индекса и триггера
        DB::transaction(function () {
            // Обновляем поле search_vector для существующих данных с использованием конфигурации русского языка
            DB::statement("
                UPDATE projects
                SET search_vector = to_tsvector('russian', coalesce(name, '') || ' ' || coalesce(description, ''))
            ");

            // Создаем триггер для автоматического обновления search_vector при вставке или обновлении
            DB::unprepared("
                CREATE OR REPLACE FUNCTION update_project_search_vector() RETURNS trigger AS $$
                BEGIN
                    NEW.search_vector := to_tsvector('russian', coalesce(NEW.name, '') || ' ' || coalesce(NEW.description, ''));
                    RETURN NEW;
                END;
                $$ LANGUAGE plpgsql;

                CREATE TRIGGER tsvectorupdate BEFORE INSERT OR UPDATE
                ON projects FOR EACH ROW EXECUTE PROCEDURE update_project_search_vector();
            ");

            // Создаем GIN-индекс для поля search_vector
            DB::statement("
                CREATE INDEX projects_search_idx ON projects USING GIN(search_vector);
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
            DB::unprepared("DROP TRIGGER IF EXISTS tsvectorupdate ON projects");
            DB::statement("DROP INDEX IF EXISTS projects_search_idx");
        });

        DB::statement("ALTER TABLE projects DROP COLUMN search_vector");
    }
}
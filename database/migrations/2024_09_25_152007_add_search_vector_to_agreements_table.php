<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddSearchVectorToAgreementsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Добавляем tsvector через сырой SQL
        DB::statement("ALTER TABLE agreements ADD COLUMN search_vector tsvector");

        // Выполняем транзакцию для создания индекса и триггера
        DB::transaction(function () {
            // Обновляем поле search_vector для существующих данных с использованием конфигурации русского языка
            DB::statement("
                UPDATE agreements
                SET search_vector = to_tsvector('russian', coalesce(name, '') || ' ' || coalesce(agr_number, '') || ' ' || coalesce(description, ''))
            ");

            // Создаем триггер для автоматического обновления search_vector при вставке или обновлении
            DB::unprepared("
                CREATE OR REPLACE FUNCTION update_search_vector() RETURNS trigger AS $$
                BEGIN
                    NEW.search_vector := to_tsvector('russian', coalesce(NEW.name, '') || ' ' || coalesce(NEW.agr_number, '') || ' ' || coalesce(NEW.description, ''));
                    RETURN NEW;
                END;
                $$ LANGUAGE plpgsql;

                CREATE TRIGGER tsvectorupdate BEFORE INSERT OR UPDATE
                ON agreements FOR EACH ROW EXECUTE PROCEDURE update_search_vector();
            ");

            // Создаем GIN-индекс для поля search_vector
            DB::statement("
                CREATE INDEX agreements_search_idx ON agreements USING GIN(search_vector);
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
            DB::unprepared("DROP TRIGGER IF EXISTS tsvectorupdate ON agreements");
            DB::statement("DROP INDEX IF EXISTS agreements_search_idx");
        });

        DB::statement("ALTER TABLE agreements DROP COLUMN search_vector");
    }
}
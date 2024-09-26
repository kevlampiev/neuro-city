<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddSearchVectorToCompanyNotesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Добавляем поле tsvector через сырой SQL
        DB::statement("ALTER TABLE company_notes ADD COLUMN search_vector tsvector");

        // Выполняем транзакцию для создания индекса и триггера
        DB::transaction(function () {
            // Обновляем поле search_vector для существующих данных с использованием конфигурации русского языка
            DB::statement("
                UPDATE company_notes
                SET search_vector = to_tsvector('russian', coalesce(note_body, ''))
            ");

            // Создаем триггер для автоматического обновления search_vector при вставке или обновлении
            DB::unprepared("
                CREATE OR REPLACE FUNCTION update_company_note_search_vector() RETURNS trigger AS $$
                BEGIN
                    NEW.search_vector := to_tsvector('russian', coalesce(NEW.note_body, ''));
                    RETURN NEW;
                END;
                $$ LANGUAGE plpgsql;

                CREATE TRIGGER tsvectorupdate BEFORE INSERT OR UPDATE
                ON company_notes FOR EACH ROW EXECUTE PROCEDURE update_company_note_search_vector();
            ");

            // Создаем GIN-индекс для поля search_vector
            DB::statement("
                CREATE INDEX company_notes_search_idx ON company_notes USING GIN(search_vector);
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
            DB::unprepared("DROP TRIGGER IF EXISTS tsvectorupdate ON company_notes");
            DB::statement("DROP INDEX IF EXISTS company_notes_search_idx");
        });

        DB::statement("ALTER TABLE company_notes DROP COLUMN search_vector");
    }
}

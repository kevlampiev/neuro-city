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
        DB::statement("ALTER TABLE messages ADD COLUMN search_vector tsvector");

        // Выполняем транзакцию для создания индекса и триггера
        DB::transaction(function () {
            // Обновляем поле search_vector для существующих данных
            // Используем функцию strip_tags для удаления HTML, если описание содержит форматирование
            DB::statement("
                UPDATE messages
                SET search_vector = to_tsvector(
                    'russian',
                    regexp_replace(coalesce(description, ''), E'<[^>]*>', '', 'g')
                )
            ");

            // Создаем функцию для автоматического обновления search_vector
            DB::unprepared("
                CREATE OR REPLACE FUNCTION update_message_search_vector() RETURNS trigger AS $$
                BEGIN
                    NEW.search_vector := to_tsvector(
                        'russian',
                        regexp_replace(coalesce(NEW.description, ''), E'<[^>]*>', '', 'g')
                    );
                    RETURN NEW;
                END;
                $$ LANGUAGE plpgsql;

                CREATE TRIGGER tsvectorupdate BEFORE INSERT OR UPDATE
                ON messages FOR EACH ROW EXECUTE PROCEDURE update_message_search_vector();
            ");

            // Создаем GIN-индекс для поля search_vector
            DB::statement("
                CREATE INDEX messages_search_idx ON messages USING GIN(search_vector);
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
            DB::unprepared("DROP TRIGGER IF EXISTS tsvectorupdate ON messages");
            DB::statement("DROP INDEX IF EXISTS messages_search_idx");
        });

        DB::statement("ALTER TABLE messages DROP COLUMN search_vector");
    }
};

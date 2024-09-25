<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddSearchVectorToCompanyEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Добавляем поле tsvector через сырой SQL
        DB::statement("ALTER TABLE company_employees ADD COLUMN search_vector tsvector");

        // Выполняем транзакцию для создания индекса и триггера
        DB::transaction(function () {
            // Обновляем поле search_vector для существующих данных с использованием конфигурации русского языка
            DB::statement("
                UPDATE company_employees
                SET search_vector = to_tsvector('russian', 
                    coalesce(name, '') || ' ' ||
                    coalesce(title, '') || ' ' ||
                    coalesce(description, '')
                )
            ");

            // Создаем триггер для автоматического обновления search_vector при вставке или обновлении
            DB::unprepared("
                CREATE OR REPLACE FUNCTION update_company_employee_search_vector() RETURNS trigger AS $$
                BEGIN
                    NEW.search_vector := to_tsvector('russian', 
                        coalesce(NEW.name, '') || ' ' ||
                        coalesce(NEW.title, '') || ' ' ||
                        coalesce(NEW.description, '')
                    );
                    RETURN NEW;
                END;
                $$ LANGUAGE plpgsql;

                CREATE TRIGGER tsvectorupdate BEFORE INSERT OR UPDATE
                ON company_employees FOR EACH ROW EXECUTE PROCEDURE update_company_employee_search_vector();
            ");

            // Создаем GIN-индекс для поля search_vector
            DB::statement("
                CREATE INDEX company_employees_search_idx ON company_employees USING GIN(search_vector);
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
            DB::unprepared("DROP TRIGGER IF EXISTS tsvectorupdate ON company_employees");
            DB::statement("DROP INDEX IF EXISTS company_employees_search_idx");
        });

        DB::statement("ALTER TABLE company_employees DROP COLUMN search_vector");
    }
}

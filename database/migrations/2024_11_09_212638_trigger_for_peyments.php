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
        // Создаем функцию-триггер для проверки согласованности
        DB::statement("
            CREATE OR REPLACE FUNCTION check_payment_consistency()
            RETURNS TRIGGER AS $$
            DECLARE
                bank_owner_id INT;
                agreement_seller_id INT;
                agreement_buyer_id INT;
            BEGIN
                -- Получаем owner_id из таблицы bank_accounts для bank_account_id из новой или обновленной записи
                SELECT owner_id INTO bank_owner_id
                FROM bank_accounts
                WHERE id = NEW.bank_account_id;

                -- Получаем seller_id и buyer_id из таблицы agreements для agreement_id из новой или обновленной записи
                SELECT seller_id, buyer_id INTO agreement_seller_id, agreement_buyer_id
                FROM agreements
                WHERE id = NEW.agreement_id;

                -- Проверяем условия согласованности
                IF bank_owner_id IS DISTINCT FROM agreement_seller_id
                   AND bank_owner_id IS DISTINCT FROM agreement_buyer_id
                   AND bank_owner_id IS DISTINCT FROM NEW.beneficiary_id THEN
                    -- Если ни одно из условий не выполняется, выбрасываем исключение
                    RAISE EXCEPTION 'Запись не согласована: owner_id не совпадает ни с seller_id, ни с buyer_id, ни с beneficiary_id';
                END IF;

                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ");

        // Создаем триггер для enforce_payment_consistency
        DB::statement("
            CREATE TRIGGER enforce_payment_consistency
            BEFORE INSERT OR UPDATE ON payments
            FOR EACH ROW
            EXECUTE FUNCTION check_payment_consistency();
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Удаляем триггер и функцию
        DB::statement('DROP TRIGGER IF EXISTS enforce_payment_consistency ON payments');
        DB::statement('DROP FUNCTION IF EXISTS check_payment_consistency');
    }

};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(<<<SQL
CREATE OR REPLACE FUNCTION process_import_adesk_operations(user_id BIGINT)
RETURNS VOID AS $$
DECLARE
    operation RECORD;
    multiplier INT;
BEGIN
    -- Проверяем, существует ли пользователь с переданным user_id
    IF NOT EXISTS (SELECT 1 FROM users WHERE id = user_id) THEN
        RAISE EXCEPTION 'Пользователь с ID % не найден', user_id;
    END IF;

    -- Цикл обработки записей import_adesk_operations
    FOR operation IN 
        SELECT * 
        FROM import_adesk_operations
        WHERE 
            adesk_type_operation_code IS NOT NULL AND 
            amount IS NOT NULL AND 
            date_open IS NOT NULL AND 
            description IS NOT NULL AND 
            bank_account_id IS NOT NULL AND 
            agreement_id IS NOT NULL AND 
            cfs_item_id IS NOT NULL
    LOOP
        -- Вставляем запись в таблицу payments
        INSERT INTO payments (
            date_open, 
            bank_account_id, 
            agreement_id, 
            amount, 
            "VAT", 
            description, 
            project_id, 
            cfs_item_id, 
            created_at
        )
        VALUES (
            operation.date_open,
            operation.bank_account_id,
            operation.agreement_id,
            CASE 
                WHEN operation.adesk_type_operation_code = 2 THEN -1 * operation.amount
                ELSE operation.amount
            END,
            operation."VAT",
            operation.description,
            operation.project_id,
            operation.cfs_item_id,
            NOW()
        );

        -- Вычисляем множитель для таблицы accruals
        SELECT CASE 
            WHEN bank_accounts.owner_id = agreements.seller_id AND operation.adesk_type_operation_code = 1 THEN  1
            WHEN bank_accounts.owner_id = agreements.buyer_id  AND operation.adesk_type_operation_code = 1 THEN -1
            WHEN bank_accounts.owner_id = agreements.seller_id AND operation.adesk_type_operation_code = 2 THEN -1
            WHEN bank_accounts.owner_id = agreements.buyer_id  AND operation.adesk_type_operation_code = 2 THEN  1
            ELSE NULL
        END INTO multiplier
        FROM bank_accounts, agreements
        WHERE 
            bank_accounts.id = operation.bank_account_id AND 
            agreements.id = operation.agreement_id;

        -- Если has_accrual = true, pl_item_id заполнено и множитель определён, вставляем запись в таблицу accruals
        IF operation.has_accrual = TRUE AND operation.pl_item_id IS NOT NULL AND multiplier IS NOT NULL THEN
            INSERT INTO accruals (
                date_open, 
                agreement_id, 
                pl_item_id, 
                project_id, 
                amount, 
                description, 
                created_by, 
                created_at
            )
            VALUES (
                operation.date_open,
                operation.agreement_id,
                operation.pl_item_id,
                operation.project_id,
                multiplier * (operation.amount - operation."VAT"),
                COALESCE(operation.accrual_description, operation.description),
                user_id,
                NOW()
            );
        END IF;

        -- Удаляем запись из import_adesk_operations
        DELETE FROM import_adesk_operations
        WHERE adesk_id = operation.adesk_id;
    END LOOP;
END;
$$ LANGUAGE plpgsql;
SQL);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared(<<<SQL
CREATE OR REPLACE FUNCTION process_import_adesk_operations(user_id BIGINT)
RETURNS VOID AS $$
DECLARE
    operation RECORD;
BEGIN
    -- Проверяем, существует ли пользователь с переданным user_id
    IF NOT EXISTS (SELECT 1 FROM users WHERE id = user_id) THEN
        RAISE EXCEPTION 'Пользователь с ID % не найден', user_id;
    END IF;

    -- Цикл обработки записей import_adesk_operations
    FOR operation IN 
        SELECT * 
        FROM import_adesk_operations
        WHERE 
            adesk_type_operation_code IS NOT NULL AND 
            amount IS NOT NULL AND 
            date_open IS NOT NULL AND 
            description IS NOT NULL AND 
            bank_account_id IS NOT NULL AND 
            agreement_id IS NOT NULL AND 
            cfs_item_id IS NOT NULL
    LOOP
        -- Вставляем запись в таблицу payments
        INSERT INTO payments (
            date_open, 
            bank_account_id, 
            agreement_id, 
            amount, 
            "VAT", 
            description, 
            project_id, 
            cfs_item_id, 
            created_at
        )
        VALUES (
            operation.date_open,
            operation.bank_account_id,
            operation.agreement_id,
            CASE 
                WHEN operation.adesk_type_operation_code = 2 THEN -1 * operation.amount
                ELSE operation.amount
            END,
            operation."VAT",
            operation.description,
            operation.project_id,
            operation.cfs_item_id,
            NOW()
        );

        -- Если has_accrual = true и pl_item_id заполнено, вставляем запись в таблицу accruals
        IF operation.has_accrual = TRUE AND operation.pl_item_id IS NOT NULL THEN
            INSERT INTO accruals (
                date_open, 
                agreement_id, 
                pl_item_id, 
                project_id, 
                amount, 
                description, 
                created_by, 
                created_at
            )
            VALUES (
                operation.date_open,
                operation.agreement_id,
                operation.pl_item_id,
                operation.project_id,
                CASE 
                    WHEN operation.adesk_type_operation_code = 2 THEN -1 * (operation.amount - operation."VAT")
                    ELSE operation.amount - operation."VAT"
                END,
                COALESCE(operation.accrual_description, operation.description),
                user_id,
                NOW()
            );
        END IF;

        -- Удаляем запись из import_adesk_operations
        DELETE FROM import_adesk_operations
        WHERE adesk_id = operation.adesk_id;
    END LOOP;
END;
$$ LANGUAGE plpgsql;
SQL);
    }
};
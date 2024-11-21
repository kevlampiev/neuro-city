<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::unprepared(<<<SQL
CREATE OR REPLACE FUNCTION update_import_adesk_operations()
RETURNS void AS $$
DECLARE
    rule RECORD;
BEGIN
    -- Цикл по всем правилам из import_adesk_rules
    FOR rule IN 
        SELECT * FROM import_adesk_rules
    LOOP
        -- Выполняем обновление записей в import_adesk_operations на основании текущего правила
        UPDATE import_adesk_operations
        SET 
            bank_account_id = rule.bank_account_id,
            agreement_id = rule.agreement_id,
            "VAT" =  COALESCE(rule."VAT", 0)*amount/(1 + COALESCE(rule."VAT", 0)),
            project_id = rule.project_id,
            cfs_item_id = rule.cfs_item_id,
            has_accrual = rule.has_accrual,
            accrual_date_open = date_open + INTERVAL '1 day' * rule.accrual_date_offset,
            pl_item_id = rule.pl_item_id,
            accrual_description = COALESCE(rule.accrual_description, description)
        WHERE 
            adesk_type_operation_code = rule.adesk_type_operation_code
            AND (rule.adesk_bank_name IS NULL OR adesk_bank_name ILIKE '%' || rule.adesk_bank_name || '%')
            AND (rule.adesk_company_name IS NULL OR adesk_company_name ILIKE '%' || rule.adesk_company_name || '%')
            AND (rule.adesk_description IS NULL OR description ILIKE '%' || rule.adesk_description || '%')
            AND (rule.adesk_cfs_category_name IS NULL OR adesk_cfs_category_name = rule.adesk_cfs_category_name)
            AND (rule.adesk_contractor_name IS NULL OR adesk_contractor_name ILIKE '%' || rule.adesk_contractor_name || '%')
            AND (rule.adesk_project_name IS NULL OR adesk_project_name ILIKE '%' || rule.adesk_project_name || '%');
    END LOOP;
END;
$$ LANGUAGE plpgsql;
SQL);
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared(<<<SQL
        CREATE OR REPLACE FUNCTION update_import_adesk_operations()
        RETURNS void AS $$
        DECLARE
            rule RECORD;
        BEGIN
            -- Цикл по всем правилам из import_adesk_rules
            FOR rule IN 
                SELECT * FROM import_adesk_rules
            LOOP
                -- Выполняем обновление записей в import_adesk_operations на основании текущего правила
                UPDATE import_adesk_operations
                SET 
                    bank_account_id = rule.bank_account_id,
                    agreement_id = rule.agreement_id,
                    "VAT" = COALESCE(rule."VAT", 1) * amount,
                    project_id = rule.project_id,
                    cfs_item_id = rule.cfs_item_id,
                    has_accrual = rule.has_accrual,
                    accrual_date_open = date_open + INTERVAL '1 day' * rule.accrual_date_offset,
                    pl_item_id = rule.pl_item_id,
                    accrual_description = COALESCE(rule.accrual_description, description)
                WHERE 
                    adesk_type_operation_code = rule.adesk_type_operation_code
                    AND (rule.adesk_bank_name IS NULL OR adesk_bank_name ILIKE '%' || rule.adesk_bank_name || '%')
                    AND (rule.adesk_company_name IS NULL OR adesk_company_name ILIKE '%' || rule.adesk_company_name || '%')
                    AND (rule.adesk_description IS NULL OR description ILIKE '%' || rule.adesk_description || '%')
                    AND (rule.adesk_cfs_category_name IS NULL OR adesk_cfs_category_name = rule.adesk_cfs_category_name)
                    AND (rule.adesk_contractor_name IS NULL OR adesk_contractor_name ILIKE '%' || rule.adesk_contractor_name || '%')
                    AND (rule.adesk_project_name IS NULL OR adesk_project_name ILIKE '%' || rule.adesk_project_name || '%');
            END LOOP;
        END;
        $$ LANGUAGE plpgsql;
        SQL);    
    }
};

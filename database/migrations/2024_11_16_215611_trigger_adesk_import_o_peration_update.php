<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement(
            "CREATE OR REPLACE FUNCTION check_agreement_consistency()
RETURNS trigger AS $$
BEGIN
    IF NEW.has_accrual THEN
        IF NEW.pl_item_id IS NULL OR NEW.accrual_date_open IS NULL THEN
            RAISE EXCEPTION 'При has_accrual = true поля pl_item_id и accrual_date_open должны быть заполнены.';
        END IF;
    END IF;

    IF EXISTS (
        SELECT 1
        FROM v_bankaccounts_agreements_agreed
        WHERE bank_account_id = NEW.bank_account_id
          AND agreement_id = NEW.agreement_id
    ) THEN
        RETURN NEW; -- Если комбинация найдена, разрешаем обновление
    ELSE
        RAISE EXCEPTION 'Комбинация bank_account_id и agreement_id не найдена в согласованных значениях.';
    END IF;
END;
$$ LANGUAGE plpgsql;");

DB::statement("CREATE TRIGGER check_agreement_consistency_trigger
BEFORE UPDATE ON import_adesk_operations
FOR EACH ROW
EXECUTE PROCEDURE check_agreement_consistency();"
);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP TRIGGER check_agreement_consistency_trigger ON import_adesk_operations");
        DB::statement("DROP FUNCTION check_agreement_consistency()");
    }
};

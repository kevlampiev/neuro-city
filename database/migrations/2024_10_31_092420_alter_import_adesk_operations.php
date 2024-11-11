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
        
        DB::unprepared('
            CREATE OR REPLACE PROCEDURE fill_import_adesk_operations_fields()
            LANGUAGE plpgsql
            AS $$
            BEGIN
                -- Обновление поля bank_account_id
                UPDATE public.import_adesk_operations AS op
                SET bank_account_id = ba.id
                FROM public.bank_accounts AS ba
                WHERE ba.adesk_id = op.adesk_bank_account_id;

                -- Обновление поля cfs_item_id
                UPDATE public.import_adesk_operations AS op
                SET cfs_item_id = ci.id
                FROM public.cfs_items AS ci
                WHERE ci.adesk_id = op.adesk_cfs_category_id;

                -- Обновление поля beneficiary_id
                UPDATE public.import_adesk_operations AS op
                SET beneficiary_id = ba.owner_id
                FROM public.bank_accounts AS ba
                WHERE ba.adesk_id = op.adesk_bank_account_id;

                -- Обновление поля project_id
                UPDATE public.import_adesk_operations AS op
                SET project_id = pr.id
                FROM public.projects AS pr
                WHERE pr.adesk_id = op.adesk_project_id;
            END;
            $$;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS fill_import_adesk_operations_fields');
    }
};

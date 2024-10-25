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
        DB::statement("
        CREATE OR REPLACE VIEW v_contract_parties AS
        SELECT a.id AS agreement_id,
               CONCAT(a.name, ' №', a.agr_number, ' от ', TO_CHAR(a.date_open, 'DD.MM.YYYY')) AS agreement_name,
               ba.id AS bank_account_id,
               a.buyer_id AS company_id,
               c.name AS company_name,
               a.seller_id AS counterparty_id,
               cp.name AS counterparty_name
        FROM agreements a
        INNER JOIN bank_accounts ba ON ba.owner_id = a.buyer_id
        INNER JOIN companies c ON c.id = a.buyer_id
        INNER JOIN companies cp ON cp.id = a.seller_id
        UNION
        SELECT a.id AS agreement_id,
               CONCAT(a.name, ' №', a.agr_number, ' от ', TO_CHAR(a.date_open, 'DD.MM.YYYY')) AS agreement_name,
               ba.id AS bank_account_id,
               a.seller_id AS company_id,
               c.name AS company_name,
               a.buyer_id AS counterparty_id,
               cp.name AS counterparty_name
        FROM agreements a
        INNER JOIN bank_accounts ba ON ba.owner_id = a.seller_id
        INNER JOIN companies c ON c.id = a.seller_id
        INNER JOIN companies cp ON cp.id = a.buyer_id;
    ");

    // Создание представления v_payments_parties
    DB::statement("
        CREATE OR REPLACE VIEW v_payments_parties AS
        SELECT p.*,
               a.company_id,
               a.company_name,
               ba.account_number,
               b.name as bank_name,
               a.agreement_name,
               pr.name AS project_name,
               cfs.name AS cfs_name
        FROM payments p
        INNER JOIN v_contract_parties a ON a.agreement_id = p.agreement_id
        INNER JOIN bank_accounts ba ON p.bank_account_id = ba.id
        INNER JOIN companies b ON ba.bank_id = b.id
        INNER JOIN cfs_items cfs ON p.cfs_item_id=cfs.id
        LEFT JOIN projects pr ON pr.id = p.project_id;
    ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Удаление представлений при откате миграции
        DB::statement('DROP VIEW IF EXISTS v_payments_parties');
        DB::statement('DROP VIEW IF EXISTS v_contract_parties');
    }
};

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
       create or replace view v_bank_accounts as
            select a.id,
                    a.account_number,
                    a.bank_id, 
                    b.name as bank, 
                    a.owner_id,
                    o.name as owner,
                    a.date_open,
                    a.date_close
            from  bank_accounts a
            inner join companies b on b.id=a.bank_id
            inner join companies o on o.id=a.owner_id
            where a.deleted_at is null 
            order by owner, bank
    ");
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS v_bank_accounts');
    }
};

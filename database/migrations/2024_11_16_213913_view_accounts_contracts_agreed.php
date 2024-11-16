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
create or replace view v_bankaccounts_agreements_agreed As
select ba.id as bank_account_id,
		a.id as agreement_id
from bank_accounts ba 
inner join agreements a on ba.owner_id = a.seller_id 
where (a.deleted_at is null ) and (ba.deleted_at is null)
union 
select ba.id as bank_account_id,
		a.id as agreement_id
from bank_accounts ba 
inner join agreements a on ba.owner_id = a.buyer_id 
where (a.deleted_at is null ) and (ba.deleted_at is null)    ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS v_bankaccounts_agreements_agreed');
    }
};

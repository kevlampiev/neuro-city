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
create or replace view v_accruals_extended AS
SELECT  acr.id,
		acr.date_open, 
		acr.agreement_id,
		CONCAT(a.name, '№ ',a.agr_number, ' от ', TO_CHAR(a.date_open, 'DD.MM.YYYY')) as agreement_name,
		a.seller_id ,
		seller.name as seller_name,
		a.buyer_id ,
		buyer.name as buyer_name,
		acr.pl_item_id,
		pl.name as pl_name,
		acr.project_id,
		pr.name as project_name,
		acr.amount,
		acr.description
from accruals acr 
inner join agreements a on a.id=acr.agreement_id 
inner join companies buyer on buyer.id = a.buyer_id 
inner join companies seller on seller.id = a.seller_id
inner join pl_items pl on pl.id = acr.pl_item_id
left join projects pr on acr.project_id = pr.id
where acr.deleted_at is null
    ");
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS v_accruals_extended');
    }
};

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
       create or replace view v_payments_extended AS
SELECT p.id,
		p.date_open, 
		p.bank_account_id,
		CONCAT('р/сч ', ba.account_number , ' в ',b.name) as account_name,
		p.agreement_id,
		CONCAT(a.name, '№ ',a.agr_number, ' от ', TO_CHAR(a.date_open, 'DD.MM.YYYY')) as agreement_name,
		ba.owner_id ,
		ba.bank_id,
		a.seller_id ,
		seller.name as seller_name,
		a.buyer_id ,
		buyer.name as buyer_name,
		p.amount,
		p.\"VAT\",
        p.description,
        p.cfs_item_id,
        cfs.name as cfs_item_name,
        p.project_id,
        pr.name as project_name
from payments p
inner join bank_accounts ba ON ba.id=p.bank_account_id 
inner join agreements a on a.id=p.agreement_id 
inner join companies b on b.id = ba.bank_id
inner join companies buyer on buyer.id = a.buyer_id 
inner join companies seller on seller.id = a.seller_id
inner join cfs_items cfs on cfs.id = p.cfs_item_id
left join projects pr on p.project_id=pr.id
where (((a.buyer_id=ba.owner_id)and (p.amount<0)) or 
		((a.seller_id =ba.owner_id) and (p.amount>0))) 
		and (p.deleted_at is null)
union 
SELECT p.id,
		p.date_open, 
		p.bank_account_id,
		CONCAT('р/сч ', ba.account_number , ' в ',b.name) as account_name,
		p.agreement_id,
		CONCAT(a.name, '№ ',a.agr_number, ' от ', TO_CHAR(a.date_open, 'DD.MM.YYYY')) as agreement_name,
		ba.owner_id ,
		ba.bank_id,
		a.seller_id ,
		seller.name as seller_name,
		a.buyer_id ,
		buyer.name as buyer_name,
		-p.amount,
		-p.\"VAT\" ,
        p.description,
        p.cfs_item_id,
        cfs.name as cfs_item_name,
        p.project_id,
        pr.name as project_name
from payments p
inner join bank_accounts ba ON ba.id=p.bank_account_id 
inner join agreements a on a.id=p.agreement_id 
inner join companies b on b.id = ba.bank_id
inner join companies buyer on buyer.id = a.buyer_id 
inner join companies seller on seller.id = a.seller_id
inner join cfs_items cfs on cfs.id = p.cfs_item_id
left join projects pr on p.project_id=pr.id
where (((a.buyer_id=ba.owner_id)and (p.amount>0)) or 
		((a.seller_id =ba.owner_id) and (p.amount<0))) 
		and (p.deleted_at is null)

    ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS v_payments_extended");
        DB::statement("
       create or replace view v_payments_extended AS
SELECT p.id,
		p.date_open, 
		p.bank_account_id,
		CONCAT('р/сч ', ba.account_number , ' в ',b.name) as account_name,
		p.agreement_id,
		CONCAT(a.name, '№ ',a.agr_number, ' от ', TO_CHAR(a.date_open, 'DD.MM.YYYY')) as agreement_name,
		ba.owner_id ,
		ba.bank_id,
		a.seller_id ,
		seller.name as seller_name,
		a.buyer_id ,
		buyer.name as buyer_name,
		p.amount,
		p.\"VAT\"
from payments p
inner join bank_accounts ba ON ba.id=p.bank_account_id 
inner join agreements a on a.id=p.agreement_id 
inner join companies b on b.id = ba.bank_id
inner join companies buyer on buyer.id = a.buyer_id 
inner join companies seller on seller.id = a.seller_id
where (((a.buyer_id=ba.owner_id)and (p.amount<0)) or 
		((a.seller_id =ba.owner_id) and (p.amount>0))) 
		and (p.deleted_at is null)
union 
SELECT p.id,
		p.date_open, 
		p.bank_account_id,
		CONCAT('р/сч ', ba.account_number , ' в ',b.name) as account_name,
		p.agreement_id,
		CONCAT(a.name, '№ ',a.agr_number, ' от ', TO_CHAR(a.date_open, 'DD.MM.YYYY')) as agreement_name,
		ba.owner_id ,
		ba.bank_id,
		a.seller_id ,
		seller.name as seller_name,
		a.buyer_id ,
		buyer.name as buyer_name,
		-p.amount,
		-p.\"VAT\" 
from payments p
inner join bank_accounts ba ON ba.id=p.bank_account_id 
inner join agreements a on a.id=p.agreement_id 
inner join companies b on b.id = ba.bank_id
inner join companies buyer on buyer.id = a.buyer_id 
inner join companies seller on seller.id = a.seller_id
where (((a.buyer_id=ba.owner_id)and (p.amount>0)) or 
		((a.seller_id =ba.owner_id) and (p.amount<0))) 
		and (p.deleted_at is null)

    ");
    }
};

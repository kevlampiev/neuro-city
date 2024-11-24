<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('import_adesk_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            //заполнение для cfs
            $table->integer('adesk_type_operation_code');
            $table->string('adesk_bank_name')->nullable();
            $table->string('adesk_company_name')->nullable();
            $table->string('adesk_description')->nullable();
            $table->string('adesk_cfs_category_name')->nullable();
            $table->string('adesk_contractor_name')->nullable();
            $table->string('adesk_project_name')->nullable();
            //заполнение для cfs
            $table->unsignedBigInteger('bank_account_id')->nullable();
            $table->unsignedBigInteger('agreement_id')->nullable();
            $table->float('VAT')->default(0);          
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('cfs_item_id')->nullable();
            //заполнение для pl
            $table->boolean('has_accrual')->default(false);
            $table->integer('accrual_date_offset')->default(0);
            $table->unsignedBigInteger('pl_item_id')->nullable(); 
            $table->string('accrual_description')->nullable();

            $table->foreign('bank_account_id')->on('bank_accounts')->references('id');
            $table->foreign('agreement_id')->on('agreements')->references('id');
            $table->foreign('project_id')->on('projects')->references('id');
            $table->foreign('cfs_item_id')->on('cfs_items')->references('id');
            $table->foreign('pl_item_id')->on('pl_items')->references('id');            
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_adesk_rules');
    }
};

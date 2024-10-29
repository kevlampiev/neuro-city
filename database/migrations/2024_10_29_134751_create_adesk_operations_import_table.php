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
        Schema::create('import_adesk_operations', function (Blueprint $table) {
            $table->bigInteger('adesk_id');
            $table->integer('adesk_type_operation_code');
            $table->float('amount');
            $table->date('date_open');
            $table->bigInteger('adesk_bank_account_id')->nullable();
            $table->string('adesk_bank_name')->nullable();
            $table->bigInteger('adesk_company_id')->nullable();
            $table->string('adesk_company_name')->nullable();
            $table->string('description')->nullable();
            $table->unsignedBigInteger('adesk_cfs_category_id')->nullable();
            $table->string('adesk_cfs_category_name')->nullable();
            $table->unsignedBigInteger('adesk_contractor_id')->nullable();
            $table->string('adesk_contractor_name')->nullable();

            $table->unsignedBigInteger('bank_account_id')->nullable();
            $table->unsignedBigInteger('agreement_id')->nullable();
            $table->float('VAT')->default(0);          
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('cfs_item_id')->nullable();
            $table->unsignedBigInteger('beneficiary_id')->nullable();

            $table->foreign('bank_account_id')->on('bank_accounts')->references('id');
            $table->foreign('agreement_id')->on('agreements')->references('id');
            $table->foreign('project_id')->on('projects')->references('id');
            $table->foreign('cfs_item_id')->on('cfs_items')->references('id');
            $table->foreign('beneficiary_id')->on('companies')->references('id');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_adesk_operations');
    }
};

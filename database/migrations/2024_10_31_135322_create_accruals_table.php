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
        Schema::create('accruals', function (Blueprint $table) {
            $table->id();
            $table->date('date_open');
            $table->unsignedBigInteger('agreement_id');
            $table->unsignedBigInteger('pl_item_id');
            $table->unsignedBigInteger('project_id');
            $table->float('amount');
            $table->string('description')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('agreement_id')->on('agreements')->references('id');
            $table->foreign('pl_item_id')->on('pl_items')->references('id');
            $table->foreign('project_id')->on('projects')->references('id');
            $table->foreign('created_by')->on('users')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accruals');
    }
};

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
        Schema::create('pl_item_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('weight')->default(0)->comment('вес группы для упорядочивания в отчетах PL');
            $table->enum('pl_section', ['sales', 'cogs', 'indirect_costs', 'DA', 'interests','tax' ]);
            $table->enum('direction', ['inflow', 'outflow']);
            $table->unsignedBigInteger('created_by')->comment('кто создал ');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('created_by')->on('users')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pl_item_groups');
    }
};

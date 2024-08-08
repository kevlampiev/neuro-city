<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCfCategoryGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cf_category_groups', function (Blueprint $table) {
            $table->id();
            $table->string('group_name', 100)->unique();
            $table->enum('category_type', ['CFO', 'CFI', 'CFF'])->default('CFO');
            $table->enum('flow_type', ['Inflows', 'Outflows'])->default('Inflows');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('cf_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id');
            
            $table->string('category_name', 100)->unique();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('group_id')->references('id')->on('cf_category_groups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cf_categories');
        Schema::dropIfExists('cf_category_groups');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agreement_note', function (Blueprint $table) {
            $table->id(); // ID записи, если нужно
            $table->unsignedBigInteger('agreement_id')->nullable(false);
            $table->unsignedBigInteger('note_id')->nullable(false);;
            $table->timestamps(); // Если нужно отслеживать время создания/обновления
            $table->foreign('agreement_id')->on('agreements')->references('id'); // Внешний ключ к таблице agreements
            $table->foreign('note_id')->on('agreements')->references('id'); // Внешний ключ к таблице notes
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agreement_note');
    }
};

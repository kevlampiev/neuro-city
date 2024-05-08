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
        Schema::table('users', function (Blueprint $table) {
            $table->string('photo')->nullable(true)->comment('фотография на аватар');
            $table->date('birthday')->nullable(true);
            $table->string('phone_number',14)->nullable(true);
            $table->boolean('is_superuser')->default(false)->comment('Является ли пользователь администратором');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('photo');
            $table->dropColumn('birthday');
            $table->dropColumn('phone_nuber');
            $table->dropColumn('is_superuser');
        });
    }
};

<?php

use App\Models\AgreementType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function addStdType(string $name, string $segment):void
    {
        $agrType = new AgreementType();
        $agrType->name = $name;
        $agrType->segment = $segment;
        $agrType->system = true;
        $agrType->save();
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->addStdType('Кредит/займ', 'finance');
        $this->addStdType('Лизинг', 'finance');
        $this->addStdType('Сделка без договора', 'operations');
        $this->addStdType('Страхование', 'operations');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Permission;

return new class extends Migration
{
    public function createPermission(string $name, string $slug)
    {
        $manageUser = new Permission();
        $manageUser->name = $name;
        $manageUser->slug = $slug;
        $manageUser->save();
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->createPermission('Изменение пользователей', 'e-user');
        $this->createPermission('Просмотр пользователей', 's-user');
        $this->createPermission('Изменение контрагентов', 'e-counterparty');
        $this->createPermission('Просмотр контрагентов', 's-counterparty');
        $this->createPermission('Изменение договоров', 'e-agreements');
        $this->createPermission('Просмотр договоров', 's-agreements');
        $this->createPermission('Изменение плановых платежей/счетов', 'e-bills');
        $this->createPermission('Просмотр плановых платежей/счетов', 's-bills');
        $this->createPermission('Изменение начислений', 'e-accruals');
        $this->createPermission('Просмотр начислений', 's-accruals');
        $this->createPermission('Изменение заявок на платеж', 'e-payment_orders');
        $this->createPermission('Просмотр заявок на платеж', 's-payment_orders');
        $this->createPermission('Изменение платежей', 'e-real_payment');
        $this->createPermission('Просмотр платежей', 's-real_payment');

        $this->createPermission('Редактирование справочников', 'e-ref_books');
        
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

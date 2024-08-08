<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Permission;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    
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
        $this->createPermission('Просмотр статей бюджета ДДС', 's-cf_categories');
        $this->createPermission('Редактирование статей бюджета ДДС', 'e-cf_categories');
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

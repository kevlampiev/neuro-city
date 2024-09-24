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
        $this->createPermission('Изменение проектов', 'e-projects');
        $this->createPermission('Просмотр проектов', 's-projects');      
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

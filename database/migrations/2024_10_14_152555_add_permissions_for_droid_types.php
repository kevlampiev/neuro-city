<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Permission;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function createPermission(string $name, string $slug)
    {
        $manageUser = new Permission();
        $manageUser->name = $name;
        $manageUser->slug = $slug;
        $manageUser->save();
    }

    public function dropPermission(string $slug)
    {
        DB::raw('DELETE FROM droid_types WHERE slug=?', [$slug]);
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->createPermission('Изменение типов дроидов', 'e-droid_types');
        $this->createPermission('Просмотр типов дроидов', 's-droid_types');
        
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->dropPermission('e-droid_types');
        $this->dropPermission('s-droid_types');
    }
};

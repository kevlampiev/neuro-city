<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('
            CREATE OR REPLACE VIEW v_task_user_relations AS 
            SELECT DISTINCT task_id, user_id FROM (
                SELECT tu.task_id, tu.user_id FROM task_user tu 
                UNION
                SELECT t.id AS task_id, t.user_id FROM tasks t 
                UNION
                SELECT t2.id AS task_id, t2.task_performer_id AS user_id FROM tasks t2
            ) AS tur;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS v_task_user_relations');
    }
};

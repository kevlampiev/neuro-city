<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            CREATE OR REPLACE VIEW v_big_search AS
            SELECT id, 'agreement' as obj_type, CONCAT(agr_number, CHR(10), name, CHR(10), description) AS obj_text, search_vector 
            FROM agreements
            UNION ALL
            SELECT id, 'company' as obj_type, CONCAT(name, CHR(10), fullname , CHR(10), header, CHR(10), post_adress) AS obj_text, search_vector 
            FROM companies c  
            union all
            select company_id, 'company_note' as obj_type, note_body as obj_text, search_vector  from company_notes 
            union all
            select id, 'document' as obj_type, description as obj_text, search_vector from documents
            union all
            select id, 'project' as obj_type, CONCAT(name, CHR(10), description) as obj_text, search_vector from projects
            union all
            select company_id, 'company_employee' as obj_type, CONCAT(name, CHR(10), title, CHR(10), description) as obj_text, search_vector from company_employees   
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW v_big_search");
    }
};

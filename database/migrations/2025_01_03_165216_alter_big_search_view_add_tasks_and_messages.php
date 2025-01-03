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
        DB::statement("
        CREATE OR REPLACE VIEW v_big_search AS
        SELECT id, 'agreement' as obj_type, CONCAT(agr_number, CHR(10), name, CHR(10), description) AS obj_text, search_vector FROM agreements WHERE deleted_at is null
        UNION ALL
        SELECT id, 'company' as obj_type, CONCAT(name, CHR(10), fullname , CHR(10), header, CHR(10), post_adress) AS obj_text, search_vector FROM companies c where deleted_at is null
        union all
        select company_id, 'company_note' as obj_type, note_body as obj_text, search_vector  from company_notes where deleted_at is null
        union all
        select id, 'document' as obj_type, description as obj_text, search_vector from documents where deleted_at is null
        union all
        select id, 'project' as obj_type, CONCAT(name, CHR(10), description) as obj_text, search_vector from projects where deleted_at is null
        union all
        select company_id, 'company_employee' as obj_type, CONCAT(name, CHR(10), title, CHR(10), description) as obj_text, search_vector from company_employees where deleted_at is null  
        union all 
        select id, 'note' as obj_type, description as obj_text, search_vector from notes where deleted_at is null
        union all
        select id, 'task' as obj_type, subject as obj_text, search_vector from tasks where deleted_at is null
        union all
        select id, 'message' as obj_type, description as obj_text, search_vector from messages where deleted_at is null
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
        CREATE OR REPLACE VIEW v_big_search AS
        SELECT id, 'agreement' as obj_type, CONCAT(agr_number, CHR(10), name, CHR(10), description) AS obj_text, search_vector FROM agreements WHERE deleted_at is null
        UNION ALL
        SELECT id, 'company' as obj_type, CONCAT(name, CHR(10), fullname , CHR(10), header, CHR(10), post_adress) AS obj_text, search_vector FROM companies c where deleted_at is null
        union all
        select company_id, 'company_note' as obj_type, note_body as obj_text, search_vector  from company_notes where deleted_at is null
        union all
        select id, 'document' as obj_type, description as obj_text, search_vector from documents where deleted_at is null
        union all
        select id, 'project' as obj_type, CONCAT(name, CHR(10), description) as obj_text, search_vector from projects where deleted_at is null
        union all
        select company_id, 'company_employee' as obj_type, CONCAT(name, CHR(10), title, CHR(10), description) as obj_text, search_vector from company_employees where deleted_at is null  
        union all 
        select id, 'note' as obj_type, description as obj_text, search_vector from notes where deleted_at is null
        ");
    }
};

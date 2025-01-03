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
        DB::unprepared(<<<SQL
        CREATE OR REPLACE FUNCTION get_task_tree_roots(
            searchStr VARCHAR,
            show_hidden BOOLEAN DEFAULT false,
            show_terminated BOOLEAN DEFAULT false,
            userId BIGINT DEFAULT NULL
        )
        RETURNS TABLE(
            id BIGINT,
            user_id BIGINT,
            task_performer_id BIGINT,
            start_date TIMESTAMP WITHOUT TIME ZONE,
            due_date TIMESTAMP WITHOUT TIME ZONE,
            terminate_date TIMESTAMP WITHOUT TIME ZONE,
            terminate_status VARCHAR,
            subject VARCHAR,
            importance VARCHAR,
            description TEXT,
            parent_task_id BIGINT,
            hidden_task BOOLEAN,
            created_at TIMESTAMP WITHOUT TIME ZONE,
            updated_at TIMESTAMP WITHOUT TIME ZONE,
            deleted_at TIMESTAMP WITHOUT TIME ZONE
        )
        LANGUAGE plpgsql
        AS $$
        BEGIN
            RETURN QUERY
            WITH RECURSIVE task_tree AS (
                SELECT 
                    t.id,
                    t.user_id,
                    t.task_performer_id,
                    t.start_date,
                    t.due_date,
                    t.terminate_date,
                    t.terminate_status,
                    t.subject,
                    t.importance,
                    t.description,
                    t.parent_task_id,
                    t.hidden_task,
                    t.created_at,
                    t.updated_at,
                    t.deleted_at
                FROM tasks t
                WHERE t.task_performer_id = userId
                  AND (t.subject ILIKE '%' || searchStr || '%' OR t.description ILIKE '%' || searchStr || '%')
                  AND (show_hidden OR t.hidden_task = false)
                  AND (show_terminated OR t.terminate_date IS NULL OR t.terminate_date > NOW())
                  AND t.deleted_at IS NULL
        
                UNION ALL
        
                SELECT 
                    child.id,
                    child.user_id,
                    child.task_performer_id,
                    child.start_date,
                    child.due_date,
                    child.terminate_date,
                    child.terminate_status,
                    child.subject,
                    child.importance,
                    child.description,
                    child.parent_task_id,
                    child.hidden_task,
                    child.created_at,
                    child.updated_at,
                    child.deleted_at
                FROM tasks child
                INNER JOIN task_tree parent
                    ON child.parent_task_id = parent.id
                   AND child.task_performer_id = userId
                   AND child.deleted_at IS NULL
            )
            SELECT DISTINCT ON (root.id)
                root.id,
                root.user_id,
                root.task_performer_id,
                root.start_date,
                root.due_date,
                root.terminate_date,
                root.terminate_status,
                root.subject,
                root.importance,
                root.description,
                root.parent_task_id,
                root.hidden_task,
                root.created_at,
                root.updated_at,
                root.deleted_at
            FROM task_tree root
            LEFT JOIN task_tree potential_parent
                ON root.parent_task_id = potential_parent.id
            WHERE potential_parent.id IS NULL;
        END;
        $$;
        SQL);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared(<<<SQL
CREATE OR REPLACE FUNCTION get_task_tree_roots(
    searchStr VARCHAR,
    show_hidden BOOLEAN DEFAULT false,
    show_terminated BOOLEAN DEFAULT false,
    userId BIGINT DEFAULT NULL
)
RETURNS TABLE(
    id BIGINT,
    user_id BIGINT,
    task_performer_id BIGINT,
    start_date TIMESTAMP WITHOUT TIME ZONE,
    due_date TIMESTAMP WITHOUT TIME ZONE,
    terminate_date TIMESTAMP WITHOUT TIME ZONE,
    terminate_status VARCHAR,
    subject VARCHAR,
    importance VARCHAR,
    description TEXT,
    parent_task_id BIGINT,
    hidden_task BOOLEAN,
    created_at TIMESTAMP WITHOUT TIME ZONE,
    updated_at TIMESTAMP WITHOUT TIME ZONE,
    deleted_at TIMESTAMP WITHOUT TIME ZONE
)
LANGUAGE plpgsql
AS $$
BEGIN
    RETURN QUERY
    WITH RECURSIVE task_tree AS (
        SELECT t.*
        FROM tasks t
        WHERE t.task_performer_id = userId
          AND (t.subject ILIKE '%' || searchStr || '%' OR t.description ILIKE '%' || searchStr || '%')
          AND (show_hidden OR t.hidden_task = false)
          AND (show_terminated OR t.terminate_date IS NULL OR t.terminate_date > NOW())
          AND t.deleted_at IS NULL

        UNION ALL

        SELECT child.*
        FROM tasks child
        INNER JOIN task_tree parent
            ON child.parent_task_id = parent.id
           AND child.task_performer_id = userId
           AND child.deleted_at IS NULL
    )
    SELECT DISTINCT ON (root.id) root.*
    FROM task_tree root
    LEFT JOIN task_tree potential_parent
        ON root.parent_task_id = potential_parent.id
    WHERE potential_parent.id IS NULL;
END;
$$;
SQL);
    }
};

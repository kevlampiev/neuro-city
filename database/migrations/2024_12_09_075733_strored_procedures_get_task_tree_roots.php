<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(<<<SQL
CREATE OR REPLACE FUNCTION get_task_tree_roots(
    searchStr VARCHAR,
    show_hidden BOOLEAN DEFAULT false,
    show_terminated BOOLEAN DEFAULT false,
    userId BIGINT DEFAULT NULL
)
RETURNS TABLE (
    id BIGINT,
    user_id BIGINT,
    task_performer_id BIGINT,
    start_date TIMESTAMP,
    due_date TIMESTAMP,
    terminate_date TIMESTAMP,
    terminate_status VARCHAR,
    subject VARCHAR,
    importance VARCHAR,
    description TEXT,
    parent_task_id BIGINT,
    hidden_task BOOLEAN,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP
) AS $$
BEGIN
    RETURN QUERY
    WITH RECURSIVE task_tree AS (
        -- Выбираем все записи, удовлетворяющие условиям фильтрации
        SELECT t.*
        FROM tasks t
        WHERE (userId IS NULL OR t.user_id = userId)
          AND (t.subject ILIKE '%' || searchStr || '%' OR t.description ILIKE '%' || searchStr || '%')
          AND (show_hidden OR t.hidden_task = false)
          AND (show_terminated OR t.terminate_date IS NULL OR t.terminate_date > NOW())
          AND t.deleted_at IS NULL

        UNION ALL

        -- Строим дерево, начиная от текущих записей
        SELECT child.*
        FROM tasks child
        INNER JOIN task_tree parent
            ON child.parent_task_id = parent.id
    )
    -- Оставляем только вершины деревьев (записи без parent_task_id или с parent_task_id, отсутствующим в отфильтрованном наборе)
    SELECT DISTINCT ON (root.id) root.*
    FROM task_tree root
    LEFT JOIN task_tree potential_parent
        ON root.parent_task_id = potential_parent.id
    WHERE potential_parent.id IS NULL; -- Убедимся, что у записи нет родителя в выборке
END;
$$ LANGUAGE plpgsql;
SQL);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP FUNCTION IF EXISTS get_task_tree_roots');
    }
};

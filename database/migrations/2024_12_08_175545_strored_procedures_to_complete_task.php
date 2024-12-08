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
        // Функция для получения поддерева задач
        $sql = <<<SQL
CREATE OR REPLACE FUNCTION fetch_subtree_ids(
    name_table TEXT,
    name_id TEXT,
    name_parent TEXT,
    base INT,
    max_levels INT,
    result_in_var BOOLEAN,
    OUT result_ids TEXT
)
RETURNS TEXT AS $$
DECLARE
    ids TEXT DEFAULT '';
    currlevel INT DEFAULT 0;
    parents TEXT := base::TEXT;
    temp_ids TEXT;
BEGIN
    result_ids := '';
    currlevel := CASE WHEN max_levels = 0 THEN 100000 ELSE 1 END;

    LOOP
        -- Добавляем текущий уровень в результат
        IF result_in_var THEN
            result_ids := result_ids || CASE WHEN LENGTH(result_ids) > 0 THEN ',' ELSE '' END || parents;
        ELSE
            ids := ids || CASE WHEN LENGTH(ids) > 0 THEN ',' ELSE '' END || parents;
        END IF;

        -- Получаем подзадачи текущего уровня
        EXECUTE format('SELECT STRING_AGG(%I::TEXT, '','') FROM %I WHERE %I IN (' || parents || ')',
            name_id, name_table, name_parent)
        INTO temp_ids;

        -- Если нет больше подзадач или достигнут предел уровней
        IF temp_ids IS NULL OR currlevel > max_levels THEN
            EXIT;
        END IF;

        parents := temp_ids;
        currlevel := currlevel + 1;
    END LOOP;

    IF NOT result_in_var THEN
        result_ids := ids;
    END IF;
END;
$$ LANGUAGE plpgsql;
SQL;

        DB::unprepared($sql);

        // Функция для завершения задачи
        $sql = <<<SQL
CREATE OR REPLACE FUNCTION po_mark_task_as_done(task_id INT)
RETURNS VOID AS $$
DECLARE
    result_ids TEXT;
BEGIN
    -- Получаем все подзадачи
    PERFORM fetch_subtree_ids('tasks', 'id', 'parent_task_id', task_id, 30, true, result_ids);
    
    -- Обновляем статус задач
    EXECUTE format('UPDATE tasks SET terminate_status = %L, terminate_date = CURRENT_DATE WHERE id IN (' || result_ids || ')', 'complete');
END;
$$ LANGUAGE plpgsql;
SQL;

        DB::unprepared($sql);

        // Функция для отмены задачи
        $sql = <<<SQL
CREATE OR REPLACE FUNCTION po_mark_task_as_canceled(task_id INT)
RETURNS VOID AS $$
DECLARE
    result_ids TEXT;
BEGIN
    -- Получаем все подзадачи
    PERFORM fetch_subtree_ids('tasks', 'id', 'parent_task_id', task_id, 30, true, result_ids);

    -- Обновляем статус задач
    EXECUTE format('UPDATE tasks SET terminate_status = %L, terminate_date = CURRENT_DATE WHERE id IN (' || result_ids || ')', 'cancel');
END;
$$ LANGUAGE plpgsql;
SQL;

        DB::unprepared($sql);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP FUNCTION IF EXISTS po_mark_task_as_done;');
        DB::unprepared('DROP FUNCTION IF EXISTS po_mark_task_as_canceled;');
        DB::unprepared('DROP FUNCTION IF EXISTS fetch_subtree_ids;');
    }
};

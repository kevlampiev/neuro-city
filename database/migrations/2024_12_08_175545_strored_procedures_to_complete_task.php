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
CREATE OR REPLACE FUNCTION terminate_task(
    taskId BIGINT,
    terminateDate TIMESTAMP DEFAULT NOW(),
    terminateStatus VARCHAR DEFAULT 'complete'
)
RETURNS VOID AS $$
DECLARE
    task RECORD;
    child_task RECORD;
BEGIN
    -- Проверка, что запись с taskId существует
    IF NOT EXISTS (SELECT 1 FROM tasks WHERE id = taskId) THEN
        RAISE EXCEPTION 'Task with ID % not found', taskId;
    END IF;

    -- Рекурсивное обновление записей
    PERFORM terminate_descendants(taskId, terminateDate, terminateStatus);
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION terminate_descendants(
    parentTaskId BIGINT,
    terminateDate TIMESTAMP,
    terminateStatus VARCHAR
)
RETURNS VOID AS $$
DECLARE
    child RECORD;
BEGIN
    -- Обновляем текущую запись
    UPDATE tasks
    SET terminate_date = terminateDate, terminate_status = terminateStatus
    WHERE id = parentTaskId AND terminate_date IS NULL;

    -- Ищем и обновляем дочерние записи
    FOR child IN 
        SELECT id
        FROM tasks
        WHERE parent_task_id = parentTaskId AND terminate_date IS NULL
    LOOP
        -- Рекурсивно обновляем потомков
        PERFORM terminate_descendants(child.id, terminateDate, terminateStatus);
    END LOOP;
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
        DB::unprepared('DROP FUNCTION IF EXISTS terminate_task CASCADE;');
        DB::unprepared('DROP FUNCTION IF EXISTS terminate_descendants CASCADE;');
    }
};

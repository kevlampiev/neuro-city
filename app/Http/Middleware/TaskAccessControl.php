<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Task;

class TaskAccessControl
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // $task = Task::find($request->route('task'))[0];
        $user = $request->user();


        $taskRouteParam = $request->route('task');

        // Проверяем, является ли параметр массивом
        if (is_array($taskRouteParam)) {
            $task = $taskRouteParam[0] ?? null; // Берём первый элемент массива, если он существует
        } else {
            $task = $taskRouteParam; // Используем напрямую
        }
    
        // Убедимся, что это объект модели Task
        if (!$task instanceof Task) {
            return response()->json(['error' => 'Invalid task parameter'], 400);
        }

        // Проверка на закрытие или отмену задачи
        if ($task->terminate_date != null) {
            if ($this->isRestrictedAction($request)) {
                return response()->json([
                    'error' => 'Action not allowed on closed or cancelled tasks',
                ], 403);
            }
        }

        // Проверка на подзадачи
        if ($request->routeIs('subtasks.*') && $task->task_performer_id !== $user->id) {
            return response()->json(['error' => 'Only the task performer can create subtasks'], 403);
        }

        // Проверка на закрытие или отмену задачи
        if (
            $request->routeIs('tasks.close', 'tasks.cancel') &&
            $task->user_id !== $user->id
        ) {
            return response()->json([
                'error' => 'Only the task owner can close or cancel tasks',
            ], 403);
        }

        // Проверка на добавление связей
        if ($request->routeIs('tasks.addContract', 'tasks.addCompany', 'tasks.addDocument')) {
            if (!in_array($user->id, [$task->user_id, $task->task_performer_id])) {
                return response()->json([
                    'error' => 'Only the task owner or performer can add links',
                ], 403);
            }
        }

        return $next($request);
    }

    /**
     * Determine if the action is restricted for closed or cancelled tasks.
     */
    protected function isRestrictedAction(Request $request): bool
    {
        return $request->routeIs(
            'tasks.comment',
            'tasks.addContract',
            'tasks.addProject'
        );
    }
}
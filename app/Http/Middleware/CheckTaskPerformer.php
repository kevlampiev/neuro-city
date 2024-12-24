<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class CheckTaskPerformer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $task = $this->resolveTask($request->route('task'));

        // Проверяем, что параметр корректный
        if (!$task instanceof Task) {
            return response()->json(['error' => 'Invalid task parameter'], 400);
        }

        // Проверяем, что пользователь является владельцем задачи
        if (Auth::id() !== $task->task_performer_id) {
            return redirect()->back()->with('error', 'Только исполнитель задачи может выполнить эту операцию');
        }

        return $next($request);
    }

    /**
     * Resolve the task from the route parameter.
     *
     * @param mixed $taskRouteParam
     * @return Task|null
     */
    private function resolveTask($taskRouteParam): ?Task
    {
        return is_array($taskRouteParam) ? ($taskRouteParam[0] ?? null) : $taskRouteParam;
    }
}

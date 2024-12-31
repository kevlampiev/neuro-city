<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class CheckTaskInteressant
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

        // Проверяем, что пользователь является заинтересованным в задаче
        if (!$task->getAllInterestedUsers()->contains(Auth::id())) {
            return redirect()->back()->with('error', 'Только пользователь, связанный с задачей, может выполнить эту операцию');
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
        if ($taskRouteParam instanceof Task) {
            // Если это уже объект Task, возвращаем его
            return $taskRouteParam;
        }
        
        if (is_array($taskRouteParam)) {
            $taskId = intval($taskRouteParam[0] ?? null); // Преобразуем в целое число
        } else {
            $taskId = intval($taskRouteParam); // Преобразуем в целое число
        }

        return Task::find($taskId);
    }
}

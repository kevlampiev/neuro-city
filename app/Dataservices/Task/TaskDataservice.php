<?php


namespace App\DataServices\Task;

use App\Http\Requests\Task\MessageRequest;
use App\Http\Requests\Task\TaskRequest;
use App\Models\Agreement;
use App\Models\Message;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Error;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class TaskDataservice
{
    public static function provideData(bool $hideClosedTasks = true): array
    {
        if ($hideClosedTasks) {
            $result = Task::query()
                ->where('parent_task_id', '=', null)
                ->where('terminate_date', '=', null)
                ->get();
        } else {
            $result = Task::query()
                ->where('parent_task_id', '=', null)
                ->get();
        }
        return ['tasks' => $result, 'hideClosedTasks' => $hideClosedTasks];
    }

    /// Вспомогательная функция, выбирающая все задачи в которых заданный пользователь
    /// является подписчиком
    private static function getTasksWhereUserIsFollower(string $searchStr, User $user): Collection
    {
        $taskFollowerIds = Arr::pluck(
            DB::select('select distinct task_id from task_user where user_id=:user_id',
                ['user_id' => $user->id]),
            'task_id');

        return Task::query()
            ->where('terminate_date', '=', null)
            ->where('terminate_date', '=', null)
            ->where('subject', 'like', $searchStr)
            ->whereIn('id', $taskFollowerIds)
            ->orderBy('due_date')
            ->get();
    }

    /// Вспомогательная функция, выбирающая все задачи в которых заданный пользователь
    /// является постановщиком
    private static function getTasksWhereUserIsMaster(string $searchStr, User $user): Collection
    {
        return Task::query()
            ->where('user_id', '=', $user->id)
            ->where('task_performer_id', '<>', $user->id)
            ->where('subject', 'like', $searchStr)
            ->where('terminate_date', '=', null)
            ->orderBy('task_performer_id')->get();
    }

    /// Вспомогательная функция, выбирающая все задачи в которых заданный пользователь
    /// является исполнителем
    private static function getTasksWhereUserIsPerformer(string $searchStr, User $user): Collection
    {
        $taskPerformer = Task::query()
            ->where('parent_task_id', '=', null)
            ->where('task_performer_id', '=', $user->id)
            ->where('subject', 'like', $searchStr)
            ->where('terminate_date', '=', null)->get();
        $pretendersArray = Task::query()
            ->where('parent_task_id', '=', null)
            ->where('user_id', '<>', $user->id)
            ->where('task_performer_id', '<>', $user->id)
            ->where('terminate_date', '=', null)->get()->pluck('id');

        while (count($pretendersArray) > 0) {
            $taskPerformer = $taskPerformer->merge(
                Task::query()
                    ->where('task_performer_id', '=', $user->id)
                    ->where('terminate_date', '=', null)
                    ->where('subject', 'like', $searchStr)
                    ->whereIn('parent_task_id', $pretendersArray)
                    ->get()
            );
            $pretendersArray = Task::query()
                ->where('user_id', '<>', $user->id)
                ->where('task_performer_id', '<>', $user->id)
                ->whereIn('parent_task_id', $pretendersArray)
                ->where('terminate_date', '=', null)->get()->pluck('id');
        }

        return $taskPerformer;
    }

    public static function provideUserTasks(Request $request, User $user): array
    {
        $filter = ($request->get('searchStr')) ?? '';
        $searchStr = '%' . str_replace(' ', '%', $filter) . '%';


        return [
            'userAssignments' => self::getTasksWhereUserIsPerformer($searchStr, $user),
            'assignedByUser' => self::getTasksWhereUserIsMaster($searchStr, $user),
            'followerTasks' => self::getTasksWhereUserIsFollower($searchStr, $user),
            'filter' => $filter
        ];
    }


    public static function provideEditor(Task $task): array
    {
        return ['task' => $task,
            'route' => ($task->id) ? 'editTask' : 'addTask',
            'users' => User::query()->orderBy('name')->get(),
            'tasks' => Task::query()->select(['id', 'subject'])->orderBy('subject')->get(),
            'importances' => ['low' => 'Низкая', 'medium' => 'Обычная', 'high' => 'Высокая'],
        ];
    }

    private static function createNewTask(array $params): Task
    {
        $task = new Task();
        $task->fill($params);
        $task->user_id = Auth::user()->id;
        $task->start_date = Carbon::now();
        $task->due_date = Carbon::now()->addDays(7);
        $task->importance = 'medium';
        return $task;

    }

    public static function create(Request $request): Task
    {
        $task = self::createNewTask([]);
        if (!empty($request->old())) $task->fill($request->old());
        return $task;
    }

    public static function createSubTask(Request $request, Task $parentTask): Task
    {
        $task = self::createNewTask(['parent_task_id' => $parentTask->id,
            'user_id' => Auth::user()->id,
            'date_start' => now(),
            'due_date' => $parentTask->due_date,
        ]);
        if (!empty($request->old())) $task->fill($request->old());
        return $task;
    }


    public static function saveChanges(TaskRequest $request, Task $task)
    {
        $task->fill($request->all());
        $task->hidden_task = $request->has('hidden_task') ? 1 : 0;

        if (!$task->user_id) $task->user_id = Auth::user()->id;
        if ($task->id) $task->updated_at = now();
        else $task->created_at = now();

        $task->save();
      
    }

    public static function store(TaskRequest $request): ?Task
    {
        try {
            $task = new Task();
            self::saveChanges($request, $task);

            // Получение параметров agreement_id, company_id из маршрута
            $agreementId = $request->route('agreement_id');
            if ($agreementId) {
                $task->agreements()->attach($agreementId);
            }
            $companyId = $request->route('company_id');
            if ($companyId) {
                $task->companies()->attach($companyId); 
            }

            session()->flash('message', 'Добавлена новая задача');
            return $task;
        } catch (Error $err) {
            session()->flash('error', 'Не удалось добавить новую задачу');
            return null;
        }
    }

    public static function edit(Request $request, Task $task)
    {
        if (!empty($request->old())) $task->fill($request->old());
    }

    public static function update(TaskRequest $request, Task $task)
    {
        try {
            self::saveChanges($request, $task);
            session()->flash('message', 'Задача обновлена');
        } catch (Error $err) {
            session()->flash('error', 'Не удалось обновить задачу');
        }
    }

    //Пометить задачу и все ее дочерние задачи, как выполненную
    public static function markAsDone(Task $task)
    {
        try {
            // Используем SELECT для вызова функции в PostgreSQL
            DB::select('SELECT terminate_task(:taskId, :terminateDate, :terminateStatus)', [
                'taskId' => $task->id,
                'terminateDate' => Carbon::now(),
                'terminateStatus' => 'complete',
            ]);
    
            session()->flash('message', 'Задача помечена как выполненная');
        } catch (\Throwable $err) {
            session()->flash('error', 'Не удалось завершить задачу: ' . $err->getMessage());
        }
    }

    //Пометить задачу и все ее дочерние задачи, как отмененную
    public static function markAsCanceled(Task $task)
    {
        try {
            DB::select('SELECT terminate_task(:taskId, :terminateDate, :terminateStatus)', [
                'taskId' => $task->id,
                'terminateDate' => Carbon::now(),
                'terminateStatus' => 'cancel',
            ]);
            session()->flash('message', 'Задача отменена');
        } catch (Error $err) {
            session()->flash('error', 'Не удалось отменить задачу');
        }
    }

    //Пометить задачу и все ее дочерние задачи, как отмененную
    public static function markAsRunning(Task $task)
    {
        try {
            $task->terminate_date = null;
            $task->terminate_status = null;
            $task->save();
            session()->flash('message', 'Задача восстановлена');
        } catch (Error $err) {
            session()->flash('error', 'Не удалось восстановить задачу');
        }
    }

    public static function setImportance(Task $task, string $importance)
    {
        try {
            $task->importance = $importance;
            $task->save();
            session()->flash('message', 'Приоритет задачи изменен');
        } catch (Error $err) {
            session()->flash('error', 'Не удалось изменить приоритет задачи');
        }

    }

    public static function createTaskFollower(Task $task): array
    {
        $alreadyAddedFollowers = DB::table('task_user')
            ->where('task_id', '=', $task->id)->pluck('user_id')->toArray();
        return [
            'task' => $task,
            'users' => User::query()->where('id', '<>', $task->user_id)
                ->where('id', '<>', $task->task_performer_id)
                ->whereNotIn('id', $alreadyAddedFollowers)
                ->get()
        ];
    }

    public static function storeTaskFollower(Request $request, Task $task)
    {
        try {
            $currentUser = Auth::user();
            if ($currentUser->id != $task->user_id) {
                throw new Exception('Добавлять подписчика может лишь постановщик задачи');
            }
            $user = User::find($request->user_id);
            $task->followers()->save($user);
            session()->flash('message', 'Подписчик успешно добавлен к задаче');
        } catch (Error $e) {
            session()->flash('error', 'Не удалось добавить подписчика к задаче');
        }
    }

    public static function detachTaskFollower(Task $task, User $user)
    {
        try {
            $currentUser = Auth::user();
            if ($currentUser->id != $task->user_id) {
                throw new Exception('Удалять подписчика может лишь постановщик задачи');
            }
            $task->followers()->detach($user);
            session()->flash('message', 'Подписчик отключен');
        } catch (Error $err) {
            session()->flash('error', 'Не удалось отключить подписчика');
        }
    }

    public static function createTaskMessage(Request $request, Task $task): Message
    {
        $message = new Message();
        $message->fill(['user_id' => Auth::user()->id,
            'task_id' => $task->id]);
        if (!empty($request->old())) $message->fill($request->old());
        return $message;
    }

    public static function storeTaskMessage(MessageRequest $request)
    {
        try {
            $message = new Message();
            $message->fill($request->all());
            if (!$message->user_id) $message->user_id = Auth::user()->id;
            $message->created_at = now();
            $message->save();
            session()->flash('message', 'Добавлено новое сообщение');
        } catch (Error $err) {
            session()->flash('error', 'Не удалось добавить сообщение');
        }
    }

}

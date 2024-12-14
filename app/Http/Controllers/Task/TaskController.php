<?php

namespace App\Http\Controllers\Task;

use App\Dataservices\Task\TaskDataservice;
use App\Http\Controllers\Controller;
use App\Http\Requests\Task\MessageRequest;
use App\Http\Requests\Task\TaskRequest;
use App\Models\Agreement;
use App\Models\Message;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{

    public function index(Request $request)
    {
        return view('tasks.projects',
            TaskDataservice::provideData());
    }

    public function viewUserTasks(Request $request, User $user)
    {

        return view('tasks.user-tasks', TaskDataservice::provideUserTasks($request, Auth::user()));
    }

    public function create(Request $request)
    {
        if (url()->previous() !== url()->current()) session(['previous_url' => url()->previous()]);
        $task = TaskDataservice::create($request);
        return view('tasks.task-edit',
            TaskDataservice::provideEditor($task));
    }

    public function createSubTask(Request $request, Task $parentTask)
    {
        if (url()->previous() !== url()->current()) session(['previous_url' => url()->previous()]);
        $task = TaskDataservice::createSubTask($request, $parentTask);
        return view('tasks.task-edit',
            TaskDataservice::provideEditor($task));
    }

    public function createTaskForAgreement(Request $request, Agreement $agreement)
    {
        if (url()->previous() !== url()->current()) session(['previous_url' => url()->previous()]);
        $task = TaskDataservice::createTaskForAgreement($request, $agreement);
        return view('tasks.task-edit',
            TaskDataservice::provideEditor($task));
    }


    public function store(TaskRequest $request): RedirectResponse
    {
        $task = TaskDataservice::store($request);
        $route = session('previous_url', route('userTasks', ['user' => Auth::user()]));
        return redirect()->to($route);
    }

    public function edit(Request $request, Task $task)
    {
        if (url()->previous() !== url()->current()) session(['previous_url' => url()->previous()]);
        TaskDataservice::edit($request, $task);
        return view('tasks.task-edit',
            TaskDataservice::provideEditor($task));
    }

    public function update(TaskRequest $request, Task $task): RedirectResponse
    {
        TaskDataservice::update($request, $task);
        $route = session('previous_url');
        return redirect()->to($route);
    }

    public function markAsDone(Task $task)
    {
        TaskDataservice::markAsDone($task);
        return redirect()->back();
    }

    public function markAsCanceled(Task $task)
    {
        TaskDataservice::markAsCanceled($task);
        return redirect()->back();
    }

    public function markAsRunning(Task $task)
    {
        TaskDataservice::markAsRunning($task);
        return redirect()->back();
    }

    public function setImportance(Task $task, string $importance)
    {
        TaskDataservice::setImportance($task, $importance);
        return redirect()->back();
    }

    public function viewTaskCard(Task $task)
    {
        return view('tasks.task-summary', ['task' => $task]);
    }

    private function getTaskUserList(Task $task): Collection
    {
        $userArray = [];
        $userArray[] = $task->user_id;
        $userArray[] = $task->task_performer_id;
        $followers = Arr::pluck(
            DB::select('select user_id from task_user where task_id=:taskId', ['taskId' => $task->id]),
            'user_id');
        foreach ($followers as $follower) {
            $userArray[] = $follower;
        }
        return collect($userArray)->unique();
    }

    public function addFollower(Request $request, Task $task)
    {
        return view('tasks.task-add-follower', TaskDataservice::createTaskFollower($task));
    }

    public function storeFollower(Request $request, Task $task)
    {
        TaskDataservice::storeTaskFollower($request, $task);
        return redirect()->route('taskCard', ['task' => $task]);
    }

    public function detachFollower(Request $request, Task $task, User $user)
    {
        TaskDataservice::detachTaskFollower($task, $user);
        return redirect()->route('taskCard', ['task' => $task]);
    }


    public function addMessage(Request $request, Task $task)
    {
        $message = TaskDataservice::createTaskMessage($request, $task);
        return view('tasks.messages.message-edit',
            ['message' => $message, 'task' => $task]);
    }

    
    public function storeMessage(MessageRequest $request, Task $task, Message $message)
    {
        TaskDataservice::storeTaskMessage($request);
        // foreach ($this->getTaskUserList($task) as $el) {
        //     if ($el != Auth::user()->id) User::find($el)->notify(new TaskCommented($task));
        // }

        return redirect()->route('taskCard', ['task' => $task, 'page' =>'messages']);
    }



}

<?php

namespace App\Http\Controllers\Task;

use App\Dataservices\Task\TaskDataservice;
use App\Http\Controllers\Controller;
use App\Http\Requests\Task\MessageRequest;
use App\Http\Requests\Task\TaskRequest;
use App\Notifications\TaskReopened;
use App\Models\Message;
use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskAddFollower;
use App\Notifications\TaskCanceled;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\TaskCommented;
use App\Notifications\TaskCompleted;
use App\Notifications\TaskCreated;
use App\Notifications\TaskDetachFollower;

class TaskController extends Controller
{

    public function index(Request $request)
    {
        return view('tasks.projects',
            TaskDataservice::provideData());
    }

    public function viewUserTasks(Request $request, User $user)
    {

        return view('tasks.user-tasks-tree', TaskDataservice::provideUserTasks($request, Auth::user()));
    }

    public function viewUserTaskList(Request $request, User $user)
    {
        return view('tasks.user-tasks-list', TaskDataservice::provideUserTaskList($request, Auth::user()));
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


    public function store(TaskRequest $request): RedirectResponse
    {
        $task = TaskDataservice::store($request);     
        if ($task->performer_id != $task->user_id) $task->performer->notify(new TaskCreated($task));
        
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
        foreach ($task->getAllInterestedUsers() as $el) {
            if ($el != Auth::user()->id) User::find($el)->notify(new TaskCompleted($task));
        }
        return redirect()->back();
    }

    public function markAsCanceled(Task $task)
    {
        TaskDataservice::markAsCanceled($task);
        foreach ($task->getAllInterestedUsers() as $el) {
            if ($el != Auth::user()->id) User::find($el)->notify(new TaskCanceled($task));
        }
        return redirect()->back();
    }

    public function markAsRunning(Task $task)
    {
        TaskDataservice::markAsRunning($task);
        foreach ($task->getAllInterestedUsers() as $el) {
            if ($el != Auth::user()->id) User::find($el)->notify(new TaskReopened($task));
        }
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

    public function addFollower(Request $request, Task $task)
    {
        return view('tasks.links.task-add-follower', TaskDataservice::createTaskFollower($task));
    }

    public function storeFollower(Request $request, Task $task)
    {
        TaskDataservice::storeTaskFollower($request, $task);
        $user = User::findOrFail($request->user_id);
        $user->notify(new TaskAddFollower($task));
        return redirect()->route('taskCard', ['task' => $task]);
    }

    public function detachFollower(Request $request, Task $task, User $user)
    {
        TaskDataservice::detachTaskFollower($task, $user);
        $user->notify(new TaskDetachFollower($task));
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
        foreach ($task->getAllInterestedUsers() as $el) {
            if ($el != Auth::user()->id) User::find($el)->notify(new TaskCommented($task));
        }

        return redirect()->route('taskCard', ['task' => $task, 'page' =>'messages']);
    }



}

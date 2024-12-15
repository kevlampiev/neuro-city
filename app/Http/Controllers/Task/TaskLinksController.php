<?php

namespace App\Http\Controllers\Task;


use App\Dataservices\Task\TaskLinksDataservice;
use App\Http\Controllers\Controller;
use App\Models\Agreement;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskLinksController extends Controller
{
    public function chooseAgreementToAttach(Request $request, Task $task):view       
    {
        return view('tasks.links.task-add-agreement', TaskLinksDataservice::provideAgreementChooseDialog($task));
    }

    public function attachAgreement(Request $request, Task $task):RedirectResponse
    {
        TaskLinksDataservice::attachAgreement($request['task_id'], $request['agreement_id']);
        return redirect()->route('taskCard', ['task'=>$task, 'page'=>'agreements']);
    }

    public function detachAgreement(Request $request, Task $task, Agreement $agreement):RedirectResponse
    {
        TaskLinksDataservice::detachAgreement($task, $agreement);
        return redirect()->route('taskCard', ['task'=>$task, 'page'=>'agreements']);
    }
}

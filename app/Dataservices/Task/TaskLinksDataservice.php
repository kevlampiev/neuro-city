<?php


namespace App\Dataservices\Task;

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

class TaskLinksDataservice
{
    
    public static function provideAgreementChooseDialog(Task $task):array
    {
        return ['task' => $task,
                'agreements' => Agreement::all()];
    }


    public static function attachAgreement($task_id, $agreement_id):void
    {
        $task = Task::findOrFail($task_id);
        $agreement = Agreement::findOrFail($agreement_id);
        if (Auth::user()->id == $task->user->id)
        {
            try {
                $task->agreements()->attach($agreement);
                session()->flash('message', 'Договор связан с задачей');
            } catch (Error $e) {
                session()->flash('error', 'Не удалось связать договор и задачу');
            }
        } else {
            session()->flash('error', 'Только постановщик задачи может прикреплять договор');
        }    
    }

    public static function detachAgreement(Task $task, Agreement $agreement)
    {
        if (Auth::user()->id == $task->user->id)
        {
            try {
                $task->agreements()->detach($agreement);
                session()->flash('message', 'Связь договора и задачи разоврана');
            } catch (Error $e) {
                session()->flash('error', 'Не удалось отвязать договор от задачи');
            }
        } else {
            session()->flash('error', 'Только постановщик задачи может разоврать связь задачи и договора');
        }    
    }

}

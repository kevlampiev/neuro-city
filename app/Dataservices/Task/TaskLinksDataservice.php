<?php


namespace App\Dataservices\Task;

use App\Models\Agreement;
use App\Models\Task;
use Error;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;

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

    public static function provideCompanyChooseDialog(Task $task):array
    {
        return ['task' => $task,
                'companies' => Company::all()];
    }


    public static function attachCompany($task_id, $company_id):void
    {
        $task = Task::findOrFail($task_id);
        $company = Company::findOrFail($company_id);
        if (Auth::user()->id == $task->user->id)
        {
            try {
                $task->companies()->attach($company);
                session()->flash('message', 'Компания связана с задачей');
            } catch (Error $e) {
                session()->flash('error', 'Не удалось связать компанию и задачу');
            }
        } else {
            session()->flash('error', 'Только постановщик задачи может прикреплять компанию');
        }    
    }

    public static function detachCompany(Task $task, Company $company)
    {
        if (Auth::user()->id == $task->user->id)
        {
            try {
                $task->companies()->detach($company);
                session()->flash('message', 'Связь компании и задачи разоврана');
            } catch (Error $e) {
                session()->flash('error', 'Не удалось отвязать компанию от задачи');
            }
        } else {
            session()->flash('error', 'Только постановщик задачи может разоврать связь задачи и компании');
        }    
    }

}

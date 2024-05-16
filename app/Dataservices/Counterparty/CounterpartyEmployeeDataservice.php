<?php


namespace App\Dataservices\Counterparty;


use App\Http\Requests\Company\CounterpartyEmployeeRequest;
use App\Models\Company;
use App\Models\CounterpartyEmployee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Error;


class CounterpartyEmployeeDataservice
{
    public static function provideEditor(CounterpartyEmployee $employee, Company $company): array
    {
        return ['employee' => $employee, 'company'=>$company];
    }

    public static function create(Request $request, Company $counterparty): CounterpartyEmployee
    {
        $employee = new CounterpartyEmployee();
        $employee->company_id = $counterparty->id;
        if (!empty($request->old())) $employee->fill($request->old());
        return $employee;
    }

    public static function edit(Request $request, CounterpartyEmployee $employee)
    {
        if (!empty($request->old())) $employee->fill($request->old());
    }


    public static function saveChanges(CounterpartyEmployeeRequest $request, CounterpartyEmployee $employee)
    {
        $employee->fill($request->all());
        if ($employee->id) {
            $employee->updated_at = now();
        } else {
            $employee->user_id = Auth::user()->id;
            $employee->created_at = now();
        }
        $employee->save();
    }

    public static function store(CounterpartyEmployeeRequest $request)
    {
        try {
            $employee = new CounterpartyEmployee();
            self::saveChanges($request, $employee);
            session()->flash('message', 'Добавлен новый сотрудник контрагента');
        } catch (Error $err) {
            session()->flash('error', 'Не удалось добавить нового сотрудника контрагента');
        }

    }

    public static function update(CounterpartyEmployeeRequest $request, CounterpartyEmployee $employee)
    {
        try {
            self::saveChanges($request, $employee);
            session()->flash('message', 'Данные сотрудника контрагента обновлены');
        } catch (Error $err) {
            session()->flash('error', 'Не удалось обновить данные сотрудника контрагента');
        }
    }

    public static function delete(CounterpartyEmployee $employee)
    {
        try {
            $employee->delete();
            session()->flash('message', 'Сотрудник контрагента удален');
        } catch (Error $err) {
            session()->flash('error', 'Не удалось удалить сотрудника контрагента');
        }
    }


}

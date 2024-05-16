<?php

namespace App\Http\Controllers\Counterparty;

use App\Dataservices\Counterparty\CounterpartyEmployeeDataservice;
use App\Http\Controllers\Controller;
use App\Http\Requests\Company\CounterpartyEmployeeRequest;
use App\Models\Company;
use App\Models\CounterpartyEmployee;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CounterpartyEmployeeController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return Application|Factory|View|Response
     */
    public function create(Request $request, Company $company)
    {
        $employee = CounterpartyEmployeeDataservice::create($request, $company);
        return view('counterparties.counterparty-employee-edit',
            CounterpartyEmployeeDataservice::provideEditor($employee, $company));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CounterpartyEmployeeRequest $request
     * @return RedirectResponse
     */
    public function store(CounterpartyEmployeeRequest $request): RedirectResponse
    {
        CounterpartyEmployeeDataservice::store($request);
        return redirect()->route('counterpartySummary',
            ['counterparty' => $request->post('company_id'),
                'page' => 'staff']);
    }


    /**
     * Show the form for editing the specified resource.
     * @param Request $request
     * @param CounterpartyEmployee $employee
     * @return View
     */
    public function edit(Request $request, CounterpartyEmployee $employee): View
    {
        CounterpartyEmployeeDataservice::edit($request, $employee);
        return view('counterparties.counterparty-employee-edit',
            CounterpartyEmployeeDataservice::provideEditor($employee, $employee->company)
        );
    }

    /**
     * Update the specified resource in storage.
     * @param CounterpartyEmployeeRequest $request
     * @param CounterpartyEmployee $employee
     * @return RedirectResponse
     */
    public function update(CounterpartyEmployeeRequest $request, CounterpartyEmployee $employee): RedirectResponse
    {

        $employee->fill($request->all())->save();
        session()->flash('message', 'Информация о сотруднике контрагента изменена');
        return redirect()
            ->route('counterpartySummary', ['counterparty' => $employee->company, 'page' => 'staff']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Counterparty $counterparty
     * @return RedirectResponse
     */
    public function destroy(CounterpartyEmployee $employee): RedirectResponse
    {
        $employee->delete();
        session()->flash('message', 'Информация о сотруднике контрагента удалена');
        return redirect()
            ->route('counterpartySummary', ['counterparty' => $employee->company, 'page' => 'staff']);
    }

}

<?php

namespace App\Http\Controllers\Accrual;

use App\Dataservices\Accrual\PlanAccrualDataservice;
use App\Http\Controllers\Controller;
use App\Http\Requests\Accrual\PlanAccrualRequest;
use App\Models\Agreement;
use App\Models\PlanAccrual;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PlanAccrualController extends Controller
{
    public function create(Request $request, Agreement $agreement)
    {
        if (url()->previous() !== url()->current()) session(['previous_url' => url()->previous()]);
        $accrual = PlanAccrualDataservice::create($request, $agreement);
        return view('accruals.plan-accruals.edit',
            PlanAccrualDataservice::providePaymentEditor($accrual));
    }

    public function store(PlanAccrualRequest $request): RedirectResponse
    {
        PlanAccrualDataservice::store($request);
        $route = session('previous_url', route('agreementSummary', ['agreement'=>$request->input('agreement_id'), 'page'=>'accruals']));
        return redirect()->to($route);
    }


    public function edit(Request $request, PlanAccrual $accrual)
    {
        
        if (url()->previous() !== url()->current()) session(['previous_url' => url()->previous()]);
        PlanAccrualDataservice::edit($request, $accrual);
        return view('accruals.plan-accruals.edit',
            PlanAccrualDataservice::providePaymentEditor($accrual));
    }

    public function update(PlanAccrualRequest $request, PlanAccrual $accrual): RedirectResponse
    {
        PlanAccrualDataservice::update($request, $accrual);
        $route = session('previous_url');
        return redirect()->to($route);
    }
 
    public function destroy(PlanAccrual $accrual): RedirectResponse
    {
        PlanAccrualDataservice::delete($accrual);
        return redirect()->back();
    }

}

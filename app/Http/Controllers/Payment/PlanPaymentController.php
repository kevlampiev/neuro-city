<?php

namespace App\Http\Controllers\Payment;

use App\Dataservices\Payment\PlanPaymentDataservice;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\PlanPaymentRequest;
use App\Models\Agreement;
use App\Models\PlanPayment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PlanPaymentController extends Controller
{
    public function create(Request $request, Agreement $agreement)
    {
        if (url()->previous() !== url()->current()) session(['previous_url' => url()->previous()]);
        $payment = PlanPaymentDataservice::create($request, $agreement);
        return view('payments.plan-payments.edit',
            PlanPaymentDataservice::providePaymentEditor($payment));
    }

    public function store(PlanPaymentRequest $request): RedirectResponse
    {
        PlanPaymentDataservice::store($request);
        $route = session('previous_url', route('agreementSummary', ['agreement'=>$request->input('agreement_id'), 'page'=>'payments']));
        return redirect()->to($route);
    }


    public function edit(Request $request, PlanPayment $payment)
    {
        
        if (url()->previous() !== url()->current()) session(['previous_url' => url()->previous()]);
        PlanPaymentDataservice::edit($request, $payment);
        return view('payments.plan-payments.edit',
            PlanPaymentDataservice::providePaymentEditor($payment));
    }

    public function update(PlanPaymentRequest $request, PlanPayment $payment): RedirectResponse
    {
        PlanPaymentDataservice::update($request, $payment);
        $route = session('previous_url');
        return redirect()->to($route);
    }
 
    public function destroy(PlanPayment $payment): RedirectResponse
    {
        // if (url()->previous() !== url()->current()) $route = url()->previous();
        PlanPaymentDataservice::delete($payment);
        return redirect()->back();
    }

}

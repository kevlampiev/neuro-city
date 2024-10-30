<?php

namespace App\Http\Controllers\Payment;

use App\Dataservices\Payment\ImportADeskOperationDataservice;
use App\Dataservices\Payment\PaymentDataservice;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\PaymentRequest;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ImportADeskOperationController extends Controller
{
    public function index(Request $request)
    {
        return view('payments.adeskImportList', ImportADeskOperationDataservice::index($request));
    }

    public function create(Request $request)
    {
        if (url()->previous() !== url()->current()) session(['previous_url' => url()->previous()]);
        $payment = PaymentDataservice::create($request);
        return view('payments.edit',
            PaymentDataservice::providePaymentEditor($payment));
    }

    public function store(PaymentRequest $request): RedirectResponse
    {
        PaymentDataservice::store($request);
        $route = session('previous_url', route('payments.index'));
        return redirect()->to($route);
    }


    public function edit(Request $request, Payment $payment)
    {
        
        if (url()->previous() !== url()->current()) session(['previous_url' => url()->previous()]);
        PaymentDataservice::edit($request, $payment);
        return view('payments.edit',
            PaymentDataservice::providePaymentEditor($payment));
    }

    public function update(PaymentRequest $request, Payment $payment): RedirectResponse
    {
        PaymentDataservice::update($request, $payment);
        $route = session('previous_url');
        return redirect()->to($route);
    }
 
    public function destroy(Payment $payment): RedirectResponse
    {
        // if (url()->previous() !== url()->current()) $route = url()->previous();
        PaymentDataservice::delete($payment);
        return redirect()->route('payments.index');
    }

}

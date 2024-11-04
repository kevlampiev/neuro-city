<?php

namespace App\Http\Controllers\Payment;

use App\Dataservices\Payment\ImportADeskOperationDataservice;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\ImportAdeskOperatinRequest;
use App\Http\Requests\Payment\PaymentRequest;
use App\Models\Impex\ImportAdeskOperation;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ImportADeskOperationController extends Controller
{
    public function index(Request $request)
    {
        return view('payments.adeskImportList', ImportADeskOperationDataservice::index($request));
    }

    // public function create(Request $request)
    // {
    //     if (url()->previous() !== url()->current()) session(['previous_url' => url()->previous()]);
    //     $payment = ImportADeskOperation::create($request);
    //     return view('payments.adeskImportEdit.blade',
    //         ImportADeskOperationDataservice::providePaymentEditor($payment));
    // }

    // public function store(PaymentRequest $request): RedirectResponse
    // {
    //     ImportADeskOperationDataservice::store($request);
    //     $route = session('previous_url', route('payments.index'));
    //     return redirect()->to($route);
    // }


    public function edit(Request $request,  $payment)
    {
        $payment = ImportADeskOperation::where('adesk_id', $payment)->first();
        if (url()->previous() !== url()->current()) session(['previous_url' => url()->previous()]);
            ImportADeskOperationDataservice::edit($request, $payment);
        return view('payments.adeskImportEdit',
            ImportADeskOperationDataservice::providePaymentEditor($payment));
    }

    public function update(ImportAdeskOperatinRequest $request, $payment): RedirectResponse
    {
        // dd($request->all());
        $payment = ImportADeskOperation::where('adesk_id', $payment)->first();
        ImportADeskOperationDataservice::update($request, $payment);
        $route = session('previous_url');
        return redirect()->to($route);
    }
 
    public function destroy(Payment $payment): RedirectResponse
    {
        // if (url()->previous() !== url()->current()) $route = url()->previous();
        ImportADeskOperationDataservice::delete($payment);
        return redirect()->route('payments.index');
    }

}

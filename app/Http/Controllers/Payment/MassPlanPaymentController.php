<?php

namespace App\Http\Controllers\Payment;

use App\Dataservices\Payment\MassPlanPaymentDataservice;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\AddMassPlanPaymentRequest;
use App\Models\Agreement;
use App\Models\PlanPayment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MassPlanPaymentController extends Controller
{
    public function create(Request $request, Agreement $agreement)
    {
        if (url()->previous() !== url()->current()) session(['previous_url' => url()->previous()]);
        $payment = MassPlanPaymentDataservice::create($request, $agreement);
        return view('payments.plan-payments.mass-add',
            MassPlanPaymentDataservice::providePaymentEditor($payment));
    }

    public function store(AddMassPlanPaymentRequest $request): RedirectResponse
    {
        MassPlanPaymentDataservice::store($request);
        $route = session('previous_url', route('agreementSummary', ['agreement'=>$request->input('agreement_id'), 'page'=>'payments']));
        return redirect()->to($route);
    }
}

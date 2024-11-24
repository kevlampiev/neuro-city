<?php

namespace App\Http\Controllers\Payment;

use App\Dataservices\Payment\AdeskRuleDataservice;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\AdeskRuleRequest;
use App\Models\AdeskRule;
use App\Models\Impex\ImportAdeskOperation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdeskRuleController
 extends Controller
{
    public function index(Request $request)
    {
        return view('payments.adeskRuleList', AdeskRuleDataservice::index($request));
    }

    public function create(Request $request, $adesk_id=null)
    {
        if ($adesk_id) $operation = ImportAdeskOperation::where('adesk_id', $adesk_id)->first();
        if (url()->previous() !== url()->current()) session(['previous_url' => url()->previous()]);
        $rule = AdeskRuleDataservice::create($request, $operation);
        return view('payments.adeskRuleEdit',
            AdeskRuleDataservice::providePaymentEditor($rule));
    }

    public function store(AdeskRuleRequest $request): RedirectResponse
    {
        AdeskRuleDataservice::store($request);
        $route = session('previous_url', route('import.adesk.rules.index'));
        return redirect()->to($route);
    }


    public function edit(Request $request,  AdeskRule $adeskRule , $payment= null)
    {
        if (url()->previous() !== url()->current()) session(['previous_url' => url()->previous()]);
        AdeskRuleDataservice::edit($request, $adeskRule);
        return view('payments.adeskRuleEdit',
                AdeskRuleDataservice::providePaymentEditor($adeskRule));
    }

    public function update(AdeskRuleRequest $request, AdeskRule $adeskRule): RedirectResponse
    {
        AdeskRuleDataservice::update($request, $adeskRule);
        $route = session('previous_url', route('import.adesk.rules.index'));
        return redirect()->to($route);
    }
 
    public function destroy(AdeskRule $rule): RedirectResponse
    {
        AdeskRuleDataservice::delete($rule);
        return redirect()->back();
    }

}

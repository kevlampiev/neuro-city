<?php

namespace App\Http\Controllers\Payment;

use App\Dataservices\Payment\BankAccountDataservice;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\BankAccountRequest;
use App\Models\BankAccount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class BankAccountController extends Controller
{
    public function index(Request $request)
    {
        return view('bankAccounts.index', BankAccountDataservice::index($request));
    }

    public function create(Request $request, $company = null)
    {
        if (url()->previous() !== url()->current()) session(['previous_url' => url()->previous()]);
        $bankAccount = BankAccountDataservice::create($request, $company);
        return view('bankAccounts.edit',
            BankAccountDataservice::provideBankAccountEditor($bankAccount));
    }

    public function store(BankAccountRequest $request): RedirectResponse
    {
        BankAccountDataservice::store($request);
        $route = session('previous_url', route('accounts.index'));
        return redirect()->to($route);
    }


    public function edit(Request $request, BankAccount $bankAccount)
    {
        if (url()->previous() !== url()->current()) session(['previous_url' => url()->previous()]);
        BankAccountDataservice::edit($request, $bankAccount);
        return view('bankAccounts.edit',
            BankAccountDataservice::provideBankAccountEditor($bankAccount));
    }

    public function update(BankAccountRequest $request, BankAccount $bankAccount): RedirectResponse
    {
        BankAccountDataservice::update($request, $bankAccount);
        $route = session('previous_url');
        return redirect()->to($route);
    }

    public function summary(BankAccount $bankAccount)
    {
        return view('bankAccounts.summary', ['model' => $bankAccount]);
    }

    public function destroy(BankAccount $bankAccount): RedirectResponse
    {
        BankAccountDataservice::delete($bankAccount);
        $route = session('previous_url');
        return redirect()->to($route);
    }

}

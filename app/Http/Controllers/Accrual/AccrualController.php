<?php

namespace App\Http\Controllers\Accrual;

use App\Dataservices\Accrual\AccrualDataservice;
use App\Http\Controllers\Controller;
use App\Http\Requests\Accrual\AccrualRequest;
use App\Models\Accrual;
use App\Models\Agreement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AccrualController extends Controller
{
    public function index(Request $request)
    {
        return view('accruals.index', AccrualDataservice::index($request));
    }

    public function create(Request $request, Agreement $agreement)
    {
        if (url()->previous() !== url()->current()) session(['previous_url' => url()->previous()]);
        $accrual = AccrualDataservice::create($request, $agreement);
        return view('accruals.edit',
            AccrualDataservice::provideAccrualEditor($accrual));
    }

    public function store(AccrualRequest $request): RedirectResponse
    {
        AccrualDataservice::store($request);
        $route = session('previous_url', route('accruals.index'));
        return redirect()->to($route);
    }


    public function edit(Request $request, Accrual $accrual)
    {
        
        if (url()->previous() !== url()->current()) session(['previous_url' => url()->previous()]);
        AccrualDataservice::edit($request, $accrual);
        return view('accruals.edit',
            AccrualDataservice::provideAccrualEditor($accrual));
    }

    public function update(AccrualRequest $request, Accrual $accrual): RedirectResponse
    {
        AccrualDataservice::update($request, $accrual);
        $route = session('previous_url');
        return redirect()->to($route);
    }
 
    public function destroy(Accrual $accrual): RedirectResponse
    {
        // if (url()->previous() !== url()->current()) $route = url()->previous();
        AccrualDataservice::delete($accrual);
        return redirect()->back();
    }

}

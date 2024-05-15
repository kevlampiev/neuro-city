<?php

namespace App\Http\Controllers\Counterparty;

use App\Dataservices\Counterparty\CounterpartyDataservice;
use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class CounterpartyController extends Controller
{
    public function index(Request $request)
    {
        return view('counterparties.counterparties', CounterpartyDataservice::index($request));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return Application|Factory|View|Response
     */
    public function create(Request $request)
    {
        $counterparty = new Company();
        if (!empty($request->old())) {
            $counterparty->fill($request->old());
            $counterparty->our_company = false;
        }
        return view('counterparties.counterparty-edit', [
            'counterparty' => $counterparty,
            'route' => 'addCounterparty',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $counterparty = new Company();
        $counterparty->fill($request->all());
        $counterparty->our_company = false;
        $counterparty->save();
        session()->flash('message', 'Добавлена новая компания');
        return redirect()->route('counterparties');
    }

    /**
     * Display the specified resource.
     *
     * @param Company $counterparty
     * @return void
     */
    public function show(Company $company)
    {
    }

    /**
     * Show the form for editing the specified resource.
     * @param Request $request
     * @param Counterparty $counterparty
     * @return View
     */
    public function edit(Request $request, Company $counterparty): View
    {
        if (!empty($request->old())) {
            $counterparty->fill($request->old());
        }
        return view('counterparties.counterparty-edit', [
            'counterparty' => $counterparty,
            'route' => 'editCounterparty',
        ]);
    }


    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param Company $company
     * @return RedirectResponse
     */
    public function update(Request $request, Company $counterparty): RedirectResponse
    {

        $counterparty->fill($request->all());
        $counterparty->our_company = false;
        $counterparty->save();
        session()->flash('message', 'Информация о компании изменена');
        return redirect()->route('counterparties');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Company $company
     * @return RedirectResponse
     */
    public function destroy(Company $company): RedirectResponse
    {
        $company->delete();
        session()->flash('message', 'Информация о компании удалена');
        return redirect()->route('counterparties');
    }

    public function summary(Company $counterparty)
    {
        return view('counterparties.counterparty-summary', ['company' => $counterparty]);
    }

}

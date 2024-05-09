<?php

namespace App\Http\Controllers\Company;

use App\DataServices\Company\CompanyDataService;
use App\Http\Controllers\Controller;
use App\Http\Requests\ManufacturerRequest;
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
        return view('Admin.counterparties.counterparties', CompanyDataservice::index($request));
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
        }
        return view('Admin.counterparties.counterparty-edit', [
            'counterparty' => $counterparty,
            'route' => 'admin.addCounterparty',
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
        $counterparty->fill($request->all())->save();
        session()->flash('message', 'Добавлена новая компания');
        return redirect()->route('companies');
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
    public function edit(Request $request, Company $company): View
    {
        if (!empty($request->old())) {
            $company->fill($request->old());
        }
        return view('Admin.counterparties.counterparty-edit', [
            'company' => $company,
            'route' => 'admin.editCounterparty',
        ]);
    }


    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param Company $company
     * @return RedirectResponse
     */
    public function update(Request $request, Company $company): RedirectResponse
    {

        $company->fill($request->all())->save();
        session()->flash('message', 'Информация о компании изменена');
        return redirect()->route('companies');
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
        return redirect()->route('admin.counterparties');
    }

    public function summary(Company $company)
    {
        return view('Admin.counterparties.counterparty-summary', ['company' => $company]);
    }

}

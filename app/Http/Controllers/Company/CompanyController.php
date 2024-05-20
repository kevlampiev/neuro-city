<?php

namespace App\Http\Controllers\Company;

use App\Dataservices\Company\CompanyDataservice;
use App\Http\Controllers\Controller;
use App\Http\Requests\Company\CompanyRequest;
use App\Models\Company;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CompanyController extends Controller
{
    public function index()
    {
        return view('companies.companies', CompanyDataservice::provideData());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return Application|Factory|View|Response
     */
    public function create(Request $request)
    {
        $company = new Company();
        if (!empty($request->old())) {
            $company->fill($request->old());
        }
        return view('companies.company-edit', [
            'company' => $company,
            'route' => 'addCompany',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param InsuranceCompanyRequest $request
     * @return RedirectResponse
     */
    public function store(CompanyRequest $request): RedirectResponse
    {
        $company = new Company();
        $company->fill($request->all());
        $company->our_company = true;
        $company->save();
        session()->flash('message', 'Добавлена новая компания группы');
        return redirect()->route('companies');
    }

    /**
     * Display the specified resource.
     *
     * @param Company $company
     * @return void
     */
    public function show(Company $company)
    {
    }

    /**
     * Show the form for editing the specified resource.
     * @param Request $request
     * @param Company $company
     * @return View
     */
    public function edit(Request $request, Company $company): View
    {
        if (!empty($request->old())) {
            $company->fill($request->old());
        }
        return view('companies.company-edit', [
            'company' => $company,
            'route' => 'editCompany',
        ]);
    }


    /**
     * Update the specified resource in storage.
     * @param InsuranceCompanyRequest $request
     * @param Company $company
     * @return RedirectResponse
     */
    public function update(CompanyRequest $request, Company $company): RedirectResponse
    {

        $company->fill($request->all());
        $company->our_company = true;
        $company->save();
        session()->flash('message', 'Информация о компании изменена');
        return redirect()->route('companies');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Company $company
     * @return RedirectResponse
     */
    public function destroy(Company $company)
    {
        $company->delete();
        session()->flash('message', 'Информация о компании удалена');
        return redirect()->route('companies');
    }

    public function summary(Company $company)
    {
        return view('companies.company-summary', ['company' => $company]);
    }
}

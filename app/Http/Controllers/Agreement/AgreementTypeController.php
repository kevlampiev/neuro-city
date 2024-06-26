<?php

namespace App\Http\Controllers\Agreement;

use App\Dataservices\Agreement\AgreementTypeDataservice;
use App\Http\Controllers\Controller;
use App\Http\Requests\Agreement\AgreementTypeRequest;
use App\Models\AgreementType;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AgreementTypeController extends Controller
{
    public function index(Request $request)
    {
        return view('agreements.agreement-types', AgreementTypeDataservice::provideData());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return Application|Factory|View|Response
     */
    public function create(Request $request)
    {
        $agrType = new AgreementType();
        if (!empty($request->old())) {
            $agrType->fill($request->old());
        }
        return view('agreements.agreement-type-edit', [
            'agrType' => $agrType,
            'route' => 'addAgrType',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AgreementTypeRequest $request
     * @return RedirectResponse
     */
    public function store(AgreementTypeRequest $request): RedirectResponse
    {
        $agrType = new AgreementType();
        $agrType->fill($request->all())->save();
        session()->flash('message', 'Добавлен новый тип договоров');
        return redirect()->route('agrTypes');
    }

    /**
     * Display the specified resource.
     *
     * @param AgreementType $agreementType
     * @return void
     */
    public function show(AgreementType $agreementType)
    {
    }

    /**
     * Show the form for editing the specified resource.
     * @param Request $request
     * @param AgreementType $agrType
     * @return View
     */
    public function edit(Request $request, AgreementType $agrType): View
    {
        if (!empty($request->old())) {
            $agrType->fill($request->old());
        }
        return view('agreements.agreement-type-edit', [
            'agrType' => $agrType,
            'route' => 'editAgrType',
        ]);
    }


    /**
     * Update the specified resource in storage.
     * @param AgreementTypeRequest $request
     * @param AgreementType $agrType
     * @return RedirectResponse
     */
    public function update(AgreementTypeRequest $request, AgreementType $agrType): RedirectResponse
    {
        $agrType->fill($request->all())->save();
        session()->flash('message', 'Информация о типе договора изменена');
        return redirect()->route('agrTypes');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param AgreementType $agrType
     * @return RedirectResponse
     */
    public function destroy(AgreementType $agrType): RedirectResponse
    {
        $agrType->delete();
        session()->flash('message', 'Информация о типе договора удалена');
        return redirect()->route('agrTypes');
    }


}

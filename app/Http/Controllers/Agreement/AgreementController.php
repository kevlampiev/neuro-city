<?php

namespace App\Http\Controllers\Agreement;

use App\Dataservices\Agreement\AgreementDataservice;
use App\DataServices\AgreementsRepo;
use App\Http\Controllers\Controller;
use App\Http\Requests\Agreement\AgreementRequest;
use App\Models\Agreement;
use App\Models\Vehicle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class AgreementController extends Controller
{
    public function index(Request $request)
    {
        return view('agreements.agreements', AgreementDataservice::index($request));
    }

    public function create(Request $request)
    {
        //event(new RealTimeMessage('Начинваем создавать новый договор'));
        if (url()->previous() !== url()->current()) session(['previous_url' => url()->previous()]);
        $agreement = AgreementDataservice::create($request);
        return view('agreements.agreement-edit',
            AgreementDataservice::provideAgreementEditor($agreement, 'addAgreement'));
    }

    public function store(AgreementRequest $request): RedirectResponse
    {
        AgreementDataservice::store($request);
        $route = session('previous_url', route('agreements'));
        return redirect()->to($route);
    }


    public function edit(Request $request, Agreement $agreement)
    {
        if (url()->previous() !== url()->current()) session(['previous_url' => url()->previous()]);
        AgreementDataservice::edit($request, $agreement);
        return view('agreements.agreement-edit',
            AgreementDataservice::provideAgreementEditor($agreement, 'admin.editAgreement'));
    }

    public function update(AgreementRequest $request, Agreement $agreement): RedirectResponse
    {
        AgreementDataservice::update($request, $agreement);
        $route = session('previous_url');
        return redirect()->to($route);
    }


    public function delete(Agreement $agreement): RedirectResponse
    {
        AgreementDataservice::delete($agreement);
        $route = session('previous_url');
        return redirect()->to($route);
    }

    public function summary(Agreement $agreement)
    {
        // $agreement->payments()->orderBy('payment_date');
        return view('agreements.agreement-summary', ['agreement' => $agreement]);
    }

    // public function addVehicle(Request $request, Agreement $agreement, Vehicle $vehicle)
    // {
    //     if ($request->isMethod('post')) {
    //         AgreementsDataservice::addVehicle($request, $agreement);
    //         return redirect()->route('admin.agreementSummary', ['agreement' => $agreement, 'page' => 'vehicles']);
    //     } else {
    //         return view('Admin.agreements.agreement-add-vehicle',
    //             AgreementsRepo::provideAddVehicleView($agreement));
    //     }
    // }

    // public function detachVehicle(Request $request, Agreement $agreement, Vehicle $vehicle): RedirectResponse
    // {
    //     AgreementsDataservice::detachVehicle($agreement, $vehicle);
    //     return redirect()->route('admin.agreementSummary', ['agreement' => $agreement, 'page' => 'vehicles']);
    // }


}

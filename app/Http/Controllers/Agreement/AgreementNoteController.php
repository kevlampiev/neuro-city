<?php

namespace App\Http\Controllers\Agreement;

use App\Dataservices\Agreement\AgreementDataservice;
use App\Dataservices\Agreement\AgreementNoteDataservice;
use App\DataServices\AgreementsRepo;
use App\Http\Controllers\Controller;
use App\Http\Requests\Agreement\AgreementRequest;
use App\Http\Requests\Agreement\AgreementNoteRequest;
use App\Models\Agreement;
use App\Models\Note;
use App\Models\Vehicle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class AgreementNoteController extends Controller
{

    public function create(Request $request, Agreement $agreement)
    {
        if (url()->previous() !== url()->current()) session(['previous_url' => url()->previous()]);
        $agreementNote = AgreementNoteDataservice::create($request);
        return view('agreements.agreement-note-edit',
            AgreementNoteDataservice::provideAgreementNoteEditor($agreementNote, $agreement));
    }

    public function store(AgreementNoteRequest $request, Agreement $agreement): RedirectResponse
    {
        
        AgreementNoteDataservice::store($request, $agreement);
        $route = session('previous_url', route('agreementSummary', ['agreement'=>$agreement, 'page'=>'notes']));
        return redirect()->to($route);
    }


    public function edit(Request $request, Agreement $agreement, Note $agreementNote)
    {
        if (url()->previous() !== url()->current()) session(['previous_url' => url()->previous()]);
        AgreementNoteDataservice::edit($request, $agreement, $agreementNote);
        return view('agreements.agreement-note-edit',
            AgreementNoteDataservice::provideAgreementNoteEditor($agreementNote, $agreement));
    }

    public function update(AgreementNoteRequest $request, Agreement $agreement, Note $agreementNote): RedirectResponse
    {
        AgreementNoteDataservice::update($request, $agreement, $agreementNote);
        $route = session('previous_url');
        return redirect()->to($route);
    }


    public function delete(Note $agreementNote): RedirectResponse
    {
        AgreementNoteDataservice::delete($agreementNote);
        $route = session('previous_url');
        return redirect()->to($route);
    }

}

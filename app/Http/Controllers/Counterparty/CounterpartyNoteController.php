<?php

namespace App\Http\Controllers\Counterparty;

use App\Dataervices\Counterparty\AgreementNotesDataservice;
use App\Models\CompanyNote;
use App\Dataservices\Counterparty\CounterpartyNoteDataservice;
use App\Http\Controllers\Controller;
use App\Http\Requests\Company\CounterpartyNoteRequest;
use App\Models\Counterparty;
use App\Models\CounterpartyNote;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Company;

class CounterpartyNoteController extends Controller
{
    public function create(Request $request, Company $counterparty)
    {
        $counterpartyNote = new CompanyNote();
        $counterpartyNote->company_id = $counterparty->id;
        if (!empty($request->old())) $counterpartyNote->fill($request->old());
        return view('counterparties.counterparty-note-edit', CounterpartyNoteDataservice::provideEditor($counterpartyNote, $counterparty));
    }

    public function store(CounterpartyNoteRequest $request, Company $counterparty): RedirectResponse
    {
        CounterpartyNoteDataservice::storeNew($request);
        return redirect()->route('counterpartySummary', ['counterparty' => $counterparty, 'page' => 'notes']);
    }

    public function edit(Request $request, CompanyNote $counterpartyNote)
    {
        if (!empty($request->old())) $counterpartyNote->fill($request->old());
        return view('counterparties.counterparty-note-edit', CounterpartyNoteDataservice::provideEditor($counterpartyNote, $counterpartyNote->company));
    }

    public function update(CounterpartyNoteRequest $request, CompanyNote $counterpartyNote): RedirectResponse
    {
        CounterpartyNoteDataservice::update($request, $counterpartyNote);
        return redirect()->route('counterpartySummary', ['counterparty' => $counterpartyNote->company, 'page' => 'notes']);
    }

    public function destroy(CompanyNote $counterpartyNote): RedirectResponse
    {
        $counterpartyId = $counterpartyNote->company->id;
        CounterpartyNoteDataservice::erase($counterpartyNote);
        return redirect()->route('counterpartySummary', ['counterparty' => $counterpartyId, 'page' => 'notes']);
    }


}

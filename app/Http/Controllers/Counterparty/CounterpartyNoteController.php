<?php

namespace App\Http\Controllers\Counterperty;

use App\DataServices\Admin\AgreementNotesDataservice;
use App\Models\CompanyNote;
use App\DataServices\Admin\CounterpartyNotesDataservice;
use App\Http\Controllers\Controller;
use App\Http\Requests\CounterpartyNoteRequest;
use App\Models\Counterparty;
use App\Models\CounterpartyNote;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Company;

class CounterpartyNoteController extends Controller
{
    public function create(Request $request, Company $company)
    {
        $counterpartyNote = new CompanyNote();
        $counterpartyNote->counterparty_id = $company->id;
        if (!empty($request->old())) $counterpartyNote->fill($request->old());
        return view('counterparties.counterparty-note-edit', CounterpartyNotesDataservice::provideEditor($counterpartyNote));
    }

    public function store(CounterpartyNoteRequest $request, Company $counterparty): RedirectResponse
    {
        CounterpartyNotesDataservice::storeNew($request);
        return redirect()->route('counterpartySummary', ['counterparty' => $counterparty, 'page' => 'notes']);
    }

    public function edit(Request $request, CompanyNote $counterpartyNote)
    {
        if (!empty($request->old())) $counterpartyNote->fill($request->old());
        return view('counterparties.counterparty-note-edit', CounterpartyNotesDataservice::provideEditor($counterpartyNote));
    }

    public function update(CounterpartyNoteRequest $request, CompanyNote $counterpartyNote): RedirectResponse
    {
        CounterpartyNotesDataservice::update($request, $counterpartyNote);
        return redirect()->route('counterpartySummary', ['counterparty' => $counterpartyNote->counterparty, 'page' => 'notes']);
    }

    public function destroy(CompanyNote $counterpartyNote): RedirectResponse
    {
        $counterpartyId = $counterpartyNote->company->id;
        CounterpartyNotesDataservice::erase($counterpartyNote);
        return redirect()->route('counterpartySummary', ['counterparty' => $counterpartyId, 'page' => 'notes']);
    }


}

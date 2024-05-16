<?php


namespace App\Dataservices\Counterparty;


use App\Http\Requests\Company\CounterpartyNoteRequest;
use App\Models\CompanyNote;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;
use PhpParser\Error;

class CounterpartyNoteDataservice
{
    public static function provideData(): array
    {
        return ['notes' => CompanyNote::all(), 'filter' => ''];
    }

    public static function provideEditor(CompanyNote $counterpartyNote, Company $company): array
    {
        return ['counterpartyNote' => $counterpartyNote, 'company' => $company] ;
    }

    public static function storeNew(CounterpartyNoteRequest $request)
    {
        $note = new CompanyNote();
        self::saveChanges($request, $note);
    }

    public static function update(CounterpartyNoteRequest $request, CompanyNote $counterpartyNote)
    {
        self::saveChanges($request, $counterpartyNote);
    }

    public static function saveChanges(CounterpartyNoteRequest $request, CompanyNote $counterpartyNote)
    {
        $counterpartyNote->fill($request->except(['id', 'created_at', 'updated_at', 'counterparty']));
        if (!$counterpartyNote->user_id) $counterpartyNote->user_id = Auth::user()->id;
        if ($counterpartyNote->id) $counterpartyNote->updated_at = now();
        else $counterpartyNote->created_at = now();

        try {
            $counterpartyNote->save();
            session()->flash('message', 'Данные заметки сохранены');
        } catch (Error $err) {
            session()->flash('error', 'Ошибка сохранения данных о заметке');
        }
    }

    public static function erase(CompanyNote $agreementNote)
    {
        try {
            $agreementNote->delete();
            session()->flash('message', 'Заметка удалена');
        } catch (Error $err) {
            session()->flash('error', 'Не удалось удалить заметку');
        }
    }
}

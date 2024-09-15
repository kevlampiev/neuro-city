<?php


namespace App\Dataservices\Agreement;

use App\Http\Requests\Agreement\AgreementNoteRequest;
use App\Models\Agreement;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpParser\Error;


class AgreementNoteDataservice
{

    
    /**
     *снабжение данными форму редактирования заметки к договору
     */
    public static function provideAgreementNoteEditor(Note $agreementNote, Agreement $agreement): array
    {
        return [
            'agreement' => $agreement,
            'agreementNote' => $agreementNote
        ];
    }

    public static function create(Request $request): Note
    {
        $agreementNote = new Note();
        if (!empty($request->old())) $agreementNote->fill($request->old());
        return $agreementNote;
    }

    public static function edit(Request $request, Agreement $agreement, Note $note)
    {
        if (!empty($request->old())) $note->fill($request->old());
    }

    public static function saveChanges(AgreementNoteRequest $request, Agreement $agreement)
    {
        // $note = new Note();
        // $note->fill($request->except(['agreement', 'agreement_id']));
        // if (!$note->user_id) $note->user_id = Auth::user()->id;
        // if ($note->id) $note->updated_at = now();
        // else $note->created_at = now();
        // if (!$note->id) {
        //     $note->save();
        //     $agreement->notes()->attach($note);
        // } else {
        //     $note->save();
        // }

        // Создаем новую заметку или получаем существующую
        // Если есть ID заметки в запросе, находим ее, иначе создаем новую
        $note = Note::find($request->input('id')) ?? new Note();
            
        // Заполняем модель данными из запроса, исключая поля 'agreement' и 'agreement_id'
        $note->fill($request->except(['agreement', 'agreement_id']));

        // Если пользователь не указан, заполняем текущим пользователем
        if (!$note->user_id) {
            $note->user_id = Auth::user()->id;
        }

        // Проверяем, новая ли это запись
        if ($note->exists) {
            // Если запись уже существует, обновляем поле updated_at
            $note->updated_at = now();
        } else {
            // Если это новая запись, заполняем поле created_at
            $note->created_at = now();
        }

        // Сохраняем заметку
        $note->save();

        // Если это новая запись, устанавливаем связь с договором
        if (!$note->wasRecentlyCreated) {
            // Если это редактирование, проверяем, есть ли связь с договором
            $agreement->notes()->syncWithoutDetaching([$note->id]); // Добавляем без дублирования связей
        } else {
            // Если новая, создаем связь
            $agreement->notes()->attach($note);
        }
    }

    public static function store(AgreementNoteRequest $request, Agreement $agreement)
    {
        try {
            $note = new Note();
            self::saveChanges($request, $agreement, $note);
            session()->flash('message', 'Добавлена заметка к договору');
        } catch (Error $err) {
            session()->flash('error', 'Не удалось добавить заметку к договору');
        }

    }

    public static function update(AgreementNoteRequest $request, Agreement $agreement, Note $note)
    {
        try {
            self::saveChanges($request, $agreement, $note);
            session()->flash('message', 'Обновлена заметка договора');
        } catch (Error $err) {
            session()->flash('error', 'Не удалось обновить заметку к договору');
        }
    }

    public static function delete(Note $note)
    {
        try {
            $note->delete();
            session()->flash('message', 'Заметка к догвору удалена');
        } catch (Error $err) {
            session()->flash('error', 'Не удалось удалить заметку к договору');
        }
    }

}

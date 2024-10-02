<?php


namespace App\Dataservices\Project;

use App\Http\Requests\Project\ProjectNoteRequest;
use App\Models\Project;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpParser\Error;


class ProjectNoteDataservice
{

    
    /**
     *снабжение данными форму редактирования заметки к договору
     */
    public static function provideProjectNoteEditor(Note $projectNote, Project $project): array
    {
        return [
            'project' => $project,
            'projectNote' => $projectNote
        ];
    }

    public static function create(Request $request): Note
    {
        $projectNote = new Note();
        if (!empty($request->old())) $projectNote->fill($request->old());
        return $projectNote;
    }

    public static function edit(Request $request, Project $project, Note $note)
    {
        if (!empty($request->old())) $note->fill($request->old());
    }

    public static function saveChanges(ProjectNoteRequest $request, Project $project)
    {
   
        $note = Note::find($request->input('id')) ?? new Note();
            
        // Заполняем модель данными из запроса, исключая поля 'Project' и 'Project_id'
        $note->fill($request->except(['project', 'project_id']));

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
            $project->notes()->syncWithoutDetaching([$note->id]); // Добавляем без дублирования связей
        } else {
            // Если новая, создаем связь
            $project->notes()->attach($note);
        }
    }

    public static function store(ProjectNoteRequest $request, Project $project)
    {
        try {
            $note = new Note();
            self::saveChanges($request, $project, $note);
            session()->flash('message', 'Добавлена заметка к проекту');
        } catch (Error $err) {
            session()->flash('error', 'Не удалось добавить заметку к проекту');
        }

    }

    public static function update(ProjectNoteRequest $request, Project $project, Note $note)
    {
        try {
            self::saveChanges($request, $project, $note);
            session()->flash('message', 'Обновлена заметка проекта');
        } catch (Error $err) {
            session()->flash('error', 'Не удалось обновить заметку проекта');
        }
    }

    public static function delete(Note $note)
    {
        try {
            $note->delete();
            session()->flash('message', 'Заметка проекта удалена');
        } catch (Error $err) {
            session()->flash('error', 'Не удалось удалить заметку проекта');
        }
    }

}

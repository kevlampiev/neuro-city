<?php


namespace App\Dataservices\Task;

use App\Http\Requests\Task\DocumentAddRequest;
use App\Http\Requests\Task\DocumentEditRequest;
use App\Http\Requests\Task\DocumentBatchAddRequest;
use App\Models\Task;
use App\Models\Document;
use App\Dataservices\Document\DocumentDataservice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Error;

class TaskDocumentDataservice
{

    public static function provideTaskDocumentEditor(Document $document=null, Task $task, $route = null): array
    {
        if (!$document) {
            $document = new Document();
        }
        $tasks = Task::whereIn('id', function ($query) {
            $query->select('task_id')
                ->from('v_task_user_relations')
                ->where('user_id', Auth::id());
        })->get();
        
        return [
            'document' => $document,
            'task_id' => $task->id,
            'tasks' => $tasks,
            'route' => $route,
        ];
    }

    public static function saveNewDocument(DocumentAddRequest $request): bool
    {
        $task = Task::find($request->input('task_id'));
        if (!$task) {
            return false;
        }

        $document = DocumentDataservice::saveFile($request);
        if (!$document) {
            return false;
        }

        $task->documents()->attach($document->id);
        return true;
    }

    public static function saveMultipleDocuments(DocumentBatchAddRequest $request): bool
    {
        $task = Task::find($request->input('agreement_id'));
        if (!$task || !$request->hasFile('document_files')) {
            return false;
        }

        $documents = DocumentDataservice::saveMultipleFiles($request);
        foreach ($documents as $document) {
            $task->documents()->attach($document->id);
        }

        return true;
    }

    public static function updateDocument(DocumentEditRequest $request, Document $document): bool
    {
        return DocumentDataservice::updateDocument($request, $document);
    }

    public static function detachDocumentFromTask(Task $task, Document $document): bool
    {
        try {
            $task->documents()->detach($document->id);
            return true;
        } catch (\Throwable $exception) {
            return false;
        }
    }
}

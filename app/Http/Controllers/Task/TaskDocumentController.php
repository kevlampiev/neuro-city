<?php

namespace App\Http\Controllers\Task;

use App\Dataservices\Task\TaskDocumentDataservice;
use App\Http\Controllers\Controller;
use App\Http\Requests\Task\DocumentAddRequest;
use App\Http\Requests\Task\DocumentBatchAddRequest;
use App\Http\Requests\Task\DocumentEditRequest;
use App\Models\Task;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class TaskDocumentController extends Controller
{
    /**
     * Переход на предыдущий URL.
     */
    private function previousUrl(): string
    {
        return url()->previous() ?? route('home');
    }

    /**
     * Открывает форму загрузки одного документа.
     */
    public function createSingeDocument(Task $task)
    {
        return view('tasks.documents.edit', 
            TaskDocumentDataservice::provideTaskDocumentEditor(null, $task, null )
        );
    }

    /**
     * Открывает форму загрузки нескольких файлов.
     */
    public function createMultipleDocuments(Task $task)
    {
        return view('tasks.documents.add-multiple', [
            'task_id' => $task->id,
            'task' => $task,
            'route' => route('addTaskManyDocuments', ['task'=>$task])
        ]);
    }

    /**
     * Открывает форму редактирования одного файла.
     */
    public function edit(Document $document, Task $task)
    {
        return view('tasks.documents.edit', 
            TaskDocumentDataservice::provideTaskDocumentEditor($document, $task, route('documents.update', $document))
        );
    }

    /**
     * Сохраняет один файл (POST).
     */
    public function storeSingle(DocumentAddRequest $request, Task $task)
    {
        $success = TaskDocumentDataservice::saveNewDocument($request);
        $message = $success ? 'Документ успешно загружен и связан с задачей.' : 'Ошибка при загрузке файла.';
        
        return redirect()->route('taskCard', [
            'task' => $request->input('task_id'),
            'page' => 'documents'
        ])->with($success ? 'message' : 'error', $message);
    }

    /**
     * Сохраняет группу файлов (POST).
     */
    public function storeMultiple(DocumentBatchAddRequest $request)
    {

        $success = TaskDocumentDataservice::saveMultipleDocuments($request);
        $message = $success ? 'Документ успешно загружен и связан с задачей.' : 'Ошибка при загрузке файла.';
    
        return redirect()->route('taskCard', [
            'task' => $request->input('task_id'),
            'page' => 'documents',
        ])->with('message', 'Документы успешно добавлены.');
      
    }


    /**
     * Редактирует единичный файл.
     */
    public function update(DocumentEditRequest $request, Document $document)
    {
        $success = TaskDocumentDataservice::updateDocument($request, $document);
        $message = $success ? 'Документ успешно обновлён.' : 'Ошибка при обновлении документа.';

        return redirect($this->previousUrl())->with($success ? 'message' : 'error', $message);
    }

    /**
     * Отвязывает файл от задачи.
     */
    public function detach(Task $task, Document $document): RedirectResponse
    {
        $success = TaskDocumentDataservice::detachDocumentFromTask($task, $document);
        $message = $success ? 'Документ успешно отвязан от задачи.' : 'Ошибка при отвязывании документа.';

        return redirect()->back()->with($success ? 'message' : 'error', $message);
    }

    /**
     * Просмотр документа.
     */
    public function preview(Document $document)
    {
        $filename = storage_path('app/public/documents/' . $document->file_name);
        $mimeType = mime_content_type($filename);

        return response()->file($filename, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . basename($filename) . '"'
        ]);
    }
}

<?php


namespace App\Dataservices\Task;

use App\Dataservices\DocumentDataservice;
use App\Http\Requests\Task\DocumentAddRequest;
use App\Http\Requests\Task\DocumentEditRequest;
use App\Models\Task;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Agreement;
use Error;

class TaskDocumentDataservice extends DocumentDataservice
{

    public static function provideAgreementDocumentEditor(Document $document, Task $task, $route = null): array
    {
        return [
            'document' => $document,
            'task_id'=>$task->id,
            'agreement' => Agreement::orderBy('date_open')->get(),
            'route' => $route
        ];
    }

    //Сохраняем новые документ и присоединяем его к договору
    public static function saveNewDocument(DocumentAddRequest $request):bool
    {
        $document = self::storeNewDocument($request);
        $agreement = Agreement::find($request->input('agreement_id'));
        if (!$document || !$agreement) return false;
        
        $agreement->documents()->attach($document);
        return true;
    }

}

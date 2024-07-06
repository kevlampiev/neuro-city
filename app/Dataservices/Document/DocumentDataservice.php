<?php


namespace App\Dataservices\Document;

use App\Http\Requests\Agreement\DocumentAddRequest;
use App\Http\Requests\DocumentEditRequest;
use App\Models\Agreement;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Error;

class DocumentDataservice
{

    public static function provideAgreementDocumentEditor(Document $document, Agreement $agreement, $route = null): array
    {
        return [
            'document' => $document,
            'agreement_id'=>$agreement->id,
            'agreements' => Agreement::orderBy('date_open')->get(),
            'route' => $route
        ];
    }


    /**
     * returns Document
     */
    public static function create(Request $request, Agreement $agreement): Document
    {
        $document = new Document();
        if (!empty($request->old())) $document->fill($request->old());
        return $document;
    }

    public static function edit(Request $request, Document $document)
    {
        if (!empty($request->old())) $document->fill($request->old());
    }

    public static function saveChanges(Request $request, Document $document)
    {

        
        $document->fill($request->except(['document_file']));
        if (!$document->user_id) $document->created_by = Auth::user()->id;
        if ($document->id) $document->updated_at = now();
        else $document->created_at = now();
        if ($request->file('document_file')) {
            Storage::delete('public/documents/' . $document->file_name);
            $file_path = $request->file('document_file')->store(config('paths.documents.put', '/public/documents'));
            $document->file_name = basename($file_path);
        }
        $document->save();
    }



    /**
     * Сохраняет полученный из запроса файл в директорию public/documents
     */
    public static function uploadNewFile(DocumentAddRequest $request):string
    {
        try {
            $file = $request->file('document_file');
                $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/documents', $filename); // Сохраняем файл в директорию 'public/documents'
            return $filename;
        } catch (Error $exception) {
            return '-- error while loading file --';
        }

        
    }


    public static function storeNewAgreementDocument(DocumentAddRequest $request):bool
    {
        try {
            if ($request->hasFile('document_file')) {
                $filename = self::uploadNewFile($request);
    
    
                // Сохранение информации о файле в базе данных
                $document = new Document();
                $document->file_name = $filename;
                $document->description = $request->input('description');
                $document->created_by = Auth::id(); // или другой пользовательский ID
                // $document->path = $path;
                $document->save();
    
                // Создание связи между договором и документом
                $agreement = Agreement::find($request->input('agreement_id'));
                $agreement->documents()->attach($document->id);
                // session()->flash('message', 'Документ успешно загружен и связан с договором.');
                return true;
 
            } else  {
                // session()->flash('error', 'Не удалось добавить новый документ.');
                return false;
            }
               
        } catch (Error $err) {
            // session()->flash('error', 'Не удалось добавить новый документ');
            return false;
        }

    }

    // public static function update(DocumentEditRequest $request, Document $document)
    // {
    //     try {
    //         self::saveChanges($request, $document);
    //         session()->flash('message', 'Данные документа обновлены');
    //     } catch (Error $err) {
    //         session()->flash('error', 'Не удалось обновить данные документа');
    //     }
    // }

    // public static function delete(Document $document)
    // {
    //     try {
    //         Storage::delete('public/documents/' . $document->file_name);
    //         $document->delete();
    //         session()->flash('message', 'Документ удален');
    //     } catch (Error $err) {
    //         session()->flash('error', 'Не удалось удалить документ');
    //     }
    // }

}

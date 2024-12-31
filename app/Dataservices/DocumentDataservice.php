<?php


namespace App\Dataservices;

use App\Http\Requests\Task\DocumentAddRequest;
use App\Http\Requests\Task\DocumentEditRequest;
use App\Models\Task;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Error;

class DocumentDataservice
{

    // /**
    //  * returns Document
    //  */
    // public static function create(Request $request): Document
    // {
    //     $document = new Document();
    //     if (!empty($request->old())) $document->fill($request->old());
    //     return $document;
    // }

    // public static function edit(Request $request, Document $document): Document
    // {
    //     if (!empty($request->old())) $document->fill($request->old());
    //     return $document;
    // }

    // public static function saveChanges(Request $request, Document $document)
    // {      
    //     $document->fill($request->except(['document_file']));
    //     if (!$document->user_id) $document->created_by = Auth::user()->id;
    //     if ($document->id) $document->updated_at = now();
    //     else $document->created_at = now();
    //     if ($request->file('document_file')) {
    //         Storage::delete('public/documents/' . $document->file_name);
    //         $file_path = $request->file('document_file')->store(config('paths.documents.put', '/public/documents'));
    //         $document->file_name = basename($file_path);
    //     }
    //     $document->save();
    // }

    
    // /**
    //  * Сохраняет полученный из запроса файл в директорию public/documents
    //  */
    // public static function uploadNewFile(Request $request):string
    // {
    //     try {
    //         $file = $request->file('document_file');
    //             $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
    //             $file->storeAs('public/documents', $filename); // Сохраняем файл в директорию 'public/documents'
    //         return $filename;
    //     } catch (Error $exception) {
    //         return '-- error while loading file --';
    //     }

        
    // }

    
    // //Сохраняем новые документ 
    // public static function storeNewDocument(Request $request):Document
    // {
    //     try {
    //         if ($request->hasFile('document_file')) {
    //             $filename = self::uploadNewFile($request);
    
    //              // Сохранение информации о файле в базе данных
    //             $document = new Document();
    //             $document->file_name = $filename;
    //             $document->description = $request->input('description');
    //             $document->created_by = Auth::id(); // или другой пользовательский ID
    //             // $document->path = $path;
    //             $document->save();
    
    //             session()->flash('message', 'Документ успешно загружен');
    //             return $document;
 
    //         } else  {
    //             session()->flash('error', 'Не удалось добавить новый документ');
    //             return null;
    //         }
               
    //     } catch (Error $err) {
    //         session()->flash('error', 'Не удалось добавить новый документ');
    //         return null;
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

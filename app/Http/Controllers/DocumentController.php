<?php

namespace App\Http\Controllers;

use App\Dataservices\Agreement\AgreementDataservice;
use App\Dataservices\Document\DocumentDataservice;
use App\Http\Controllers\Controller;
use App\Http\Requests\Agreement\DocumentAddRequest;
use App\Http\Requests\DocumentEditRequest;
use App\Models\Agreement;
use App\Models\Document;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class DocumentController extends Controller
{

    public function uploadFile(Request $request)
    {
        $request->validate([
            'file' => 'required|max:25600', // Максимум 25MB
        ]);
    
        try {
            $file = $request->file('file');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs(config('paths.documents.put', 'public/documents'), $filename);;

            return response()->json([
                'message' => 'Файл успешно загружен',
                'filename' => $filename,
                'original_name' => $file->getClientOriginalName(),
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ошибка загрузки файла: ' . $e->getMessage()], 500);
        }
    }

    public function preview(Document $document)
    {
        $filename =storage_path('app/public/documents/' . $document->file_name);
        $mimeType = mime_content_type($filename);
        return response()->file($filename, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . basename($filename) . '"'
        ]);
    }



}

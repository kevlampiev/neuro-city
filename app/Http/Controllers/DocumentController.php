<?php

namespace App\Http\Controllers;

use App\Dataservices\Document\DocumentDataservice;
use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentAddRequest;
use App\Http\Requests\DocumentEditRequest;
use App\Models\Agreement;
use App\Models\Document;
use App\Models\Vehicle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{


    private function previousUrl(): string
    {
        $route = url()->previous();
        if (preg_match('/.{1,}summary$/i', $route)) $route .= '/documents';
        return $route;
    }

    private function storeUrl(Agreement $agreement)
    {
            session(['previous_url' => route('agreementSummary', ['agreement' => $agreement, 'page' => 'documents'])]);        
    }

    public function createAgreementDocument(Request $request, Agreement $agreement)
    {
        $Document = DocumentDataservice::create($request, $agreement);
       $this->storeUrl($agreement);
        if (url()->current() != url()->previous()) session(['previous_url' => url()->previous()]);
        return view('agreements.agreement-document-edit',
            DocumentDataservice::provideAgreementDocumentEditor($Document, $agreement, 'addDocument'));
    }

    public function storeAgreementDocument(Request $request)
    {
        // Сохранение файла на сервере
        if ($request->hasFile('document_file')) {
            $file = $request->file('document_file');
            $path = $file->store('documents');

            // Сохранение информации о файле в базе данных
            $document = new Document();
            $document->file_name = $path;
            $document->description = $request->input('description');
            $document->created_by = Auth::id(); // или другой пользовательский ID
            // $document->path = $path;
            $document->save();

            // Создание связи между договором и документом
            $agreement = Agreement::find($request->input('agreement_id'));
            $agreement->documents()->attach($document->id);

            return redirect()->route("agreementSummary", ['agreement'=>$agreement])->with('message', 'Документ успешно загружен и связан с договором.');
        }

        return redirect()->back()->with('error', 'Ошибка при загрузке файла.');

    }


//     public function edit(Request $request, Document $document)
//     {
// //        $this->storeUrl($document->vehicle_id, $document->agreement_id);
//         if (url()->current() != url()->previous()) session(['previous_url' => url()->previous()]);
//         DocumentsDataservice::edit($request, $document);
//         return view('Admin.document-edit',
//             DocumentsDataservice::provideDocumentEditor($document, 'admin.editVehicleDocument'));
//     }

//     //Используется другой Request, подразумевается что файл уже есть на диске,
//     // проверять его присутсвие в форме не обязательно
//     public function update(DocumentEditRequest $request, Document $Document)
//     {
//         DocumentsDataservice::update($request, $Document);
//         $route = session()->pull('previous_url');
//         return redirect()->to($route);
//     }

    public function preview(Document $document)
    {
        $filename =storage_path('app/public/documents/' . $document->file_name);
        // $filename = Storage::url($document->file_name);
        return response()->file($filename);
    }

//     public function delete(Document $Document): RedirectResponse
//     {
//         DocumentsDataservice::delete($Document);
// //        $route = session()->pull('previous_url');
//         return redirect()->back();
//     }


}

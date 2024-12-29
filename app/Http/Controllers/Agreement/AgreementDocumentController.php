<?php

namespace App\Http\Controllers\Agreement;

use App\Dataservices\Agreement\AgreementDocumentDataservice;
use App\Http\Controllers\Controller;
use App\Http\Requests\Agreement\DocumentAddRequest;
use App\Http\Requests\Agreement\DocumentBatchAddRequest;
use App\Http\Requests\Agreement\DocumentEditRequest;
use App\Models\Agreement;
use App\Models\Document;
use Illuminate\Contracts\View\View as ContractsViewView;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\View\View as ViewView;

class AgreementDocumentController extends Controller
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
    public function createSingeDocument(Agreement $agreement)
    {
        return view('agreements.documents.edit', 
            AgreementDocumentDataservice::provideAgreementDocumentEditor(null, $agreement, null )
        );
    }

    /**
     * Открывает форму загрузки нескольких файлов.
     */
    public function createMultipleDocuments(Agreement $agreement)
    {
        return view('agreements.documents.add-multiple', [
            'agreement_id' => $agreement->id,
            'agreement' => $agreement,
            'route' => route('addAgreementManyDocuments', ['agreement'=>$agreement])
        ]);
    }

    /**
     * Открывает форму редактирования одного файла.
     */
    public function edit(Document $document, Agreement $agreement)
    {
        return view('agreements.agreement-document-edit', 
            AgreementDocumentDataservice::provideAgreementDocumentEditor($document, $agreement, route('documents.update', $document))
        );
    }

    /**
     * Сохраняет один файл (POST).
     */
    public function storeSingle(DocumentAddRequest $request)
    {
        $success = AgreementDocumentDataservice::saveNewDocument($request);
        $message = $success ? 'Документ успешно загружен и связан с договором.' : 'Ошибка при загрузке файла.';

        return redirect()->route('agreementSummary', [
            'agreement' => $request->input('agreement_id'),
            'page' => 'documents'
        ])->with($success ? 'message' : 'error', $message);
    }

    /**
     * Сохраняет группу файлов (POST).
     */
    public function storeMultiple(DocumentBatchAddRequest $request)
    {
        $success = AgreementDocumentDataservice::saveMultipleDocuments($request);
        $message = $success ? 'Файлы успешно загружены и связаны с договором.' : 'Ошибка при загрузке файлов.';

        return redirect()->route('agreementSummary', [
            'agreement' => $request->input('agreement_id'),
            'page' => 'documents'
        ])->with($success ? 'message' : 'error', $message);
    }

    /**
     * Редактирует единичный файл.
     */
    public function update(DocumentEditRequest $request, Document $document)
    {
        $success = AgreementDocumentDataservice::updateDocument($request, $document);
        $message = $success ? 'Документ успешно обновлён.' : 'Ошибка при обновлении документа.';

        return redirect($this->previousUrl())->with($success ? 'message' : 'error', $message);
    }

    /**
     * Отвязывает файл от договора.
     */
    public function detach(Agreement $agreement, Document $document): RedirectResponse
    {
        $success = AgreementDocumentDataservice::detachDocumentFromAgreement($agreement, $document);
        $message = $success ? 'Документ успешно отвязан от договора.' : 'Ошибка при отвязывании документа.';

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

<?php

namespace App\Dataservices\Agreement;

use App\Dataservices\Document\DocumentDataservice;
use App\Http\Requests\Agreement\DocumentAddRequest;
use App\Http\Requests\Agreement\DocumentEditRequest;
use App\Http\Requests\Agreement\DocumentBatchAddRequest;
use App\Models\Agreement;
use App\Models\Document;

class AgreementDocumentDataservice
{
    public static function provideAgreementDocumentEditor(Document $document=null, Agreement $agreement, $route = null): array
    {
        if (!$document) {
            $document = new Document();
            $document->agreement_id;
        }
        return [
            'document' => $document,
            'agreement_id' => $agreement->id,
            'agreements' => Agreement::orderBy('date_open')->get(),
            'route' => $route,
        ];
    }

    public static function saveNewDocument(DocumentAddRequest $request): bool
    {
        $agreement = Agreement::find($request->input('agreement_id'));
        if (!$agreement) {
            return false;
        }

        $document = DocumentDataservice::saveFile($request);
        if (!$document) {
            return false;
        }

        $agreement->documents()->attach($document->id);
        return true;
    }

    public static function saveMultipleDocuments(DocumentBatchAddRequest $request): bool
    {
        $agreement = Agreement::find($request->input('agreement_id'));
        if (!$agreement || !$request->hasFile('document_files')) {
            return false;
        }

        $documents = DocumentDataservice::saveMultipleFiles($request);
        foreach ($documents as $document) {
            $agreement->documents()->attach($document->id);
        }

        return true;
    }

    public static function updateDocument(DocumentEditRequest $request, Document $document): bool
    {
        return DocumentDataservice::updateDocument($request, $document);
    }

    public static function detachDocumentFromAgreement(Agreement $agreement, Document $document): bool
    {
        try {
            $agreement->documents()->detach($document->id);
            return true;
        } catch (\Throwable $exception) {
            return false;
        }
    }
}

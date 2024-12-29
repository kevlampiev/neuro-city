<?php

namespace App\Http\Requests\Agreement;

use Illuminate\Foundation\Http\FormRequest;

class DocumentBatchAddRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'document_files.*' => 'required|file', // Каждый файл обязателен
            'agreement_id' => 'required|exists:agreements,id',
        ];
    }

    public function attributes(): array
    {
        return [
            'document_files' => 'Файлы документов',
            'agreement_id' => 'ID договора',
        ];
    }
}

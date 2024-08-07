<?php

namespace App\Http\Requests\Agreement;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DocumentAddRequest extends FormRequest
{
/**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'description' => 'string|min:3',
            'file_name' => 'string|min:3',
            'document_file' => 'file|required',
            'agreement_id' => 'required|exists:agreements,id',
        ];
    }

    public function attributes(): array
    {
        return [
            'file_name' => 'Файл докумена',
            'description' => 'Описание',
            'document_file' => 'Файл документа',
            'agreement_id' => 'ID договора',
        ];
    }

}

<?php

namespace App\Http\Requests\Agreement;

use Illuminate\Foundation\Http\FormRequest;

class AgreementNoteRequest extends FormRequest
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
            'description' => 'string|required|max:254',
            'agreement_id' => 'exists:agreements,id'
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'текст заметки',
            'agreement_id' => 'ссылка на договор'
        ];
    }

}

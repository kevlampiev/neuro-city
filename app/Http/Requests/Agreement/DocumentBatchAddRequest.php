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
            'uploaded_files.*' => 'required|string', // Пути к уже загруженным файлам
            'agreement_id' => 'required|exists:agreements,id', // Проверка существования договора
        ];
    }

    public function attributes(): array
    {
        return [
            'uploaded_files' => 'Загруженные файлы',
            'agreement_id' => 'ID договора',
        ];
    }
}
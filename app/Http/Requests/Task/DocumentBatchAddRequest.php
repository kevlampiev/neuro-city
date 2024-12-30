<?php

namespace App\Http\Requests\Task;

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
            'task_id' => 'required|exists:tasks,id', // Проверка существования задачи
        ];
    }

    public function attributes(): array
    {
        return [
            'uploaded_files' => 'Загруженные файлы',
            'task_id' => 'ID задачи',
        ];
    }
}
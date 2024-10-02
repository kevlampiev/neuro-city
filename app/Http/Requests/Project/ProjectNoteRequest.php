<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class ProjectNoteRequest extends FormRequest
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
            'description' => 'string|required',
            'project_id' => 'exists:projects,id'
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'текст заметки',
            'Project_id' => 'ссылка на проект'
        ];
    }

}

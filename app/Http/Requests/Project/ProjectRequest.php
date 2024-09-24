<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectRequest extends FormRequest
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
        dd('111');
        return [
            'name' => 'required|string|min:4',
            'description' => 'string|nullable',
            'date_open' => 'required|date',
            'date_close' => 'nullable|date|after_or_equal:date_open',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Название проекта',
            'description' => 'Описание проекта',
            'date_open' => 'Дата начала проекта',
            'date_close' => 'Дата окончания проекта',
        ];
    }

}

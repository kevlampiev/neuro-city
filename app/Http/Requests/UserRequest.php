<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|min:3',
            'email' => 'required|email',
            'phone_number' => 'numeric',
            // 'role' => 'required|string',
            'birthday' => 'nullable|date',
            // 'post' => 'nullable|string',
        ];

    }

    public function attributes(): array
    {
        return [
            'name' => 'ФИО',
            'email' => 'e-mail',
            'phone_number' => 'номер телефона',
            'role' => 'Роль в администрировании',
            'birthday' => 'Дата рождения',
            'post' => 'Должность',
        ];
    }
}

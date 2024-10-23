<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BankAccountRequest extends FormRequest
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

    public function rules(): array
    {
        return [
            'account_number' => 'required|string|size:20',  // Проверка на длину в 20 символов
            'date_open' => 'required|date',
            'date_close' => 'nullable|date|after:date_open',  // Дата закрытия должна быть после даты открытия
            'owner_id' => 'required|exists:companies,id',  // Проверка существования в таблице companies
            'bank_id' => 'required|exists:companies,id',   // Проверка существования в таблице companies
            'description' => 'nullable|string',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'account_number' => 'Номер банковского счета',
            'date_open' => 'Дата открытия',
            'date_close' => 'Дата закрытия',
            'owner_id' => 'Владелец',
            'bank_id' => 'Банк',
            'description' => 'Описание',
        ];
    }

}

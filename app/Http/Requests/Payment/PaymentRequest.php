<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaymentRequest extends FormRequest
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
            'date_open' => 'date|nullable',
            'bank_account_id' => 'required|exists:bank_accounts,id',
            'agreement_id' => 'required|exists:agreements,id',
            'amount' => 'required|numeric',
            'VAT' => 'required|numeric',
            'description' => 'required|string|min:5',
            'project_id' => 'nullable|exists:projects,id',
            'cfs_item_id' => 'required|exists:cfs_items,id',
        ];
    }

    public function attributes(): array
    {
        return [
            'date_open' => 'Дата операции',
            'bank_account_id' => 'Банковский счет',
            'agreement_id' => 'Договор',
            'amount' => 'Сумма',
            'VAT' => 'Сумма НДС',
            'description' => 'Описание',
            'project_id' => 'Проект',
            'cfs_item_id' => 'Статья ОДДС',
        ];
    }

}

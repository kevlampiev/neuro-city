<?php

namespace App\Http\Requests\Accrual;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AccrualRequest extends FormRequest
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
    
    protected function prepareForValidation()
    {

        if ($this->input('project_id') == "*БЕЗ ПРОЕКТА*") {
            $this->merge([
                'project_id' => null,
            ]);
        }
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'date_open' => 'date|required',
            'agreement_id' => 'required|exists:agreements,id',
            'amount' => 'required|numeric',
            'description' => 'required|string|min:5',
            'pl_item_id' => 'required|exists:pl_items,id',
        ];
    }


    public function attributes(): array
    {
        return [
            'date_open' => 'Дата начисления',
            'agreement_id' => 'Договор',
            'amount' => 'Сумма',
            'description' => 'Описание',
            'project_id' => 'Проект',
            'pl_item_id' => 'Статья ОФР',
        ];
    }

}

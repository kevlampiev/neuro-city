<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class AddMassPlanPaymentRequest extends FormRequest
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
            'repeat_count' => 'required|integer|min:1',
            'initial_date' => 'date|required',
            'shifted_date' => 'date|required',
            'agreement_id' => 'required|exists:agreements,id',
            'amount' => 'required|numeric',
            'VAT' => 'required|numeric',
            'description' => 'nullable|string',
            'project_id' => 'nullable|exists:projects,id',
            'cfs_item_id' => 'required|exists:cfs_items,id',
        ];
    }


    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $amount = $this->input('amount');
            $vat = $this->input('VAT');
            
            // Проверка согласованности amount и VAT
            if (($amount < 0 && $vat > 0) || ($amount > 0 && $vat < 0)) {
                $validator->errors()->add('VAT', 'Сумма и НДС должны быть одинаковы по знаку');
            }
            if (abs($amount / 6) <= abs($vat*0.99)) {
                $validator->errors()->add('VAT', 'НДС не может быть более 20% суммы платежа');
            }

        });
    }

    public function attributes(): array
    {
        return [
            'repeat_count' => 'количество повторений',
            'initial_date' => 'Первоначальная дата платежа',
            'shifted_date' => 'Планируемая дата платежа (смещенная относительно начальной)',
            'agreement_id' => 'Договор',
            'amount' => 'Сумма платежа, включая НДС',
            'VAT' => 'НДС',
            'description' => 'Основание платежа',
            'project_id' => 'Проект',
            'cfs_item_id' => 'Статья ОДДС',
        ];
    }

}

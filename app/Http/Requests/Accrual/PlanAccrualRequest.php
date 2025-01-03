<?php

namespace App\Http\Requests\Accrual;

use Illuminate\Foundation\Http\FormRequest;

class PlanAccrualRequest extends FormRequest
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
            'initial_date' => 'date|required',
            'shifted_date' => 'date|required',
            'agreement_id' => 'required|exists:agreements,id',
            'amount_per_unit' => 'required|numeric',
            'units_count' => 'required|numeric',
            'description' => 'nullable|string',
            'project_id' => 'nullable|exists:projects,id',
            'pl_item_id' => 'required|exists:pl_items,id',
            'product_id' => 'required|exists:products,id',
        ];
    }


    public function attributes(): array
    {
        return [
            'initial_date' => 'Первоначальная дата поставки товара (оказания услуги)',
            'shifted_date' => 'Планируемая дата поставки товара (оказания услуги)',
            'agreement_id' => 'Договор',
            'amount_per_unit' => 'Цена единицы',
            'units_count' => 'Количество',
            'description' => 'Описание',
            'project_id' => 'Проект',
            'pl_item_id' => 'Статья ОПиУ',
            'product_id' => 'Продукт/услуга',
        ];
    }

}

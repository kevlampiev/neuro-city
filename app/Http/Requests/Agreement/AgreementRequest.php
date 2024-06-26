<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AgreementRequest extends FormRequest
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
            'name' => 'required|string|min:4',
            'seller_id' => 'required|exists:companies,id',
            'buyer_id' => 'required|exists:companies,id',
            'agr_number' => 'required|string|min:3',
            'description' => 'string|nullable',
            'date_open' => 'date|nullable',
            'date_close' => 'date|nullable',
            'real_date_close' => 'date|nullable',
            'agreement_type_id' => 'required|exists:agreement_types,id',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Наименование типа договора',
            'agr_number' => 'Номер договора',
            'seller_id' => 'Поставщик услуг по договору',
            'buyer_id' => 'Покупатель по договору',
            'description' => 'Описание договора',
            'date_open' => 'Дата заключения договора',
            'date_close' => 'Дата окончания договора (планируемая)',
            'real_date_close' => 'Дата окончания договора (реальная)',
            
        ];
    }

}

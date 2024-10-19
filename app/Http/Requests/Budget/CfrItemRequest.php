<?php

namespace App\Http\Requests\Budget;

use Illuminate\Foundation\Http\FormRequest;

class CfrItemRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3',
            'group_id' => 'required|exists:cfs_item_groups,id'
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'наименование статьи',
            'group_id'=>'группа статей',
        ];
    }

}

<?php

namespace App\Http\Requests\Budget;

use Illuminate\Foundation\Http\FormRequest;

class PlGroupRequest extends FormRequest
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
            'weight' => 'required|numeric|min:0'
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'наименование статьи',
            'weight' => 'вес статьи',
        ];
    }

}
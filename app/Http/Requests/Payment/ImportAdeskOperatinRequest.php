<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ImportAdeskOperatinRequest extends FormRequest
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

    /*
    *  Кбирем возможную грязь
    */
    protected function prepareForValidation()
    {

        if ($this->input('project_id') == "*БЕЗ ПРОЕКТА*") {
            $this->merge([
                'project_id' => null,
            ]);
        }
        
        $this->merge([
            'has_accrual' => ($this->input('has_accrual') == "on"),
        ]);
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
            'has_accrual' => 'required|boolean',
            'accrual_date_open' => 'nullable|date',
            'pl_item_id' => 'nullable|exists:pl_items,id',
            'accrual_description' => 'nullable|string',
        ];
    }


    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $amount = $this->input('amount');
            $vat = $this->input('VAT');
            $bankAccountId = $this->input('bank_account_id');
            $agreementId = $this->input('agreement_id');
            $beneficiaryId = $this->input('beneficiary_id');
            $hasAccrual = ($this->input('has_accrual')=="on");
            $accrualDateOpen = $this->input('accrual_date_open');
            $plItemId = $this->input('pl_item_id');
            
            // Проверка согласованности amount и VAT
            if (($amount < 0 && $vat > 0) || ($amount > 0 && $vat < 0)) {
                $validator->errors()->add('VAT', 'Сумма и НДС должны быть одинаковы по знаку');
            }
            if (abs($amount / 6) <= abs($vat * 0.99)) {
                $validator->errors()->add('VAT', 'НДС не может быть более 20% суммы платежа');
            }
    
            // Проверка согласованности owner_id из bank_accounts и связанных полей
            $bankAccount = \App\Models\BankAccount::find($bankAccountId);
            $agreement = \App\Models\Agreement::find($agreementId);
    
            if ($bankAccount && $agreement) {
                $ownerId = $bankAccount->owner_id;
                $sellerId = $agreement->seller_id;
                $buyerId = $agreement->buyer_id;
    
                if ($ownerId != $sellerId && $ownerId != $buyerId && $ownerId != $beneficiaryId) {
                    $validator->errors()->add('bank_account_id', 'Банковский счет должен принадлежать одной из сторон по договору или бенефициару');
                }
            }
    
            // Проверка полей accrual_date_open и pl_item_id при has_accrual == true
            if ($hasAccrual) {
                if (empty($accrualDateOpen)) {
                    $validator->errors()->add('accrual_date_open', 'Поле "Дата открытия начисления" обязательно, если начисление включено.');
                }
                if (empty($plItemId)) {
                    $validator->errors()->add('pl_item_id', 'Поле "Статья доходов/расходов" обязательно, если начисление включено.');
                }
            }
        });
    
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

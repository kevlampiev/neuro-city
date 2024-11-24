<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdeskRuleRequest extends FormRequest
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
            "name" => 'required|string|min:5',
	        "adesk_type_operation_code" => 'required|int|between:1,3',
	        "adesk_bank_name" => "nullable|string",
	        "adesk_company_name" => "nullable|string",
	        "adesk_description" => "nullable|string",
	        "adesk_cfs_category_name" => "nullable|string",
	        "adesk_contractor_name" => "nullable|string",
	        "adesk_project_name" => "nullable|string",
	        "bank_account_id" => 'required|exists:bank_accounts,id',
	        "agreement_id" => 'required|exists:agreements,id',
	        "VAT" => 'required|numeric|min:0|max:0.2',
	        "project_id" => 'nullable|exists:projects,id',
	        "cfs_item_id" => 'required|exists:cfs_items,id',
            "has_accrual" => 'required|boolean',
	        "accrual_date_offset" => 'required|int',
	        "pl_item_id" => 'nullable|exists:pl_items,id',
            'accrual_description' => 'nullable|string',
        ];
    }


    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            
            $bankAccountId = $this->input('bank_account_id');
            $agreementId = $this->input('agreement_id');
            
            $hasAccrual = ($this->input('has_accrual')=="on");
            $plItemId = $this->input('pl_item_id');
            
    
            // Проверка согласованности owner_id из bank_accounts и связанных полей
            $bankAccount = \App\Models\BankAccount::find($bankAccountId);
            $agreement = \App\Models\Agreement::find($agreementId);
    
            if ($bankAccount && $agreement) {
                $ownerId = $bankAccount->owner_id;
                $sellerId = $agreement->seller_id;
                $buyerId = $agreement->buyer_id;
    
                if ($ownerId != $sellerId && $ownerId != $buyerId) {
                    $validator->errors()->add('bank_account_id', 'Банковский счет должен принадлежать одной из сторон по договору');
                }
            }
    
            // Проверка полей accrual_date_open и pl_item_id при has_accrual == true
            if ($hasAccrual) {
                if (empty($plItemId)) {
                    $validator->errors()->add('pl_item_id', 'Поле "Статья доходов/расходов" обязательно, если начисление генерируется.');
                }
            }

            //Хотя бы одно поле adesk должно присутствовать
            if (!$this->input('adesk_bank_name') &&
                    !$this->input('adesk_company_name') &&
                    !$this->input('adesk_description') &&
                    !$this->input('adesk_cfs_category_name') &&
                    !$this->input('adesk_contractor_name') &&
                    !$this->input('adesk_project_name')) 
                    {
                        $validator->errors()->add('adesk_bank_name', 'Хотя бы одно значение из операции Adesk должно быть указана для идентификации операции');
                        $validator->errors()->add('adesk_company_name', 'Хотя бы одно значение из операции Adesk должно быть указана для идентификации операции');
                        $validator->errors()->add('adesk_description', 'Хотя бы одно значение из операции Adesk должно быть указана для идентификации операции');
                        $validator->errors()->add('adesk_cfs_category_name', 'Хотя бы одно значение из операции Adesk должно быть указана для идентификации операции');
                        $validator->errors()->add('adesk_contractor_name', 'Хотя бы одно значение из операции Adesk должно быть указана для идентификации операции');
                        $validator->errors()->add('adesk_project_name', 'Хотя бы одно значение из операции Adesk должно быть указана для идентификации операции');
                    }
        });
    
    }


    public function attributes(): array
    {
        return [
            "name" => 'Название правила',
	        "adesk_type_operation_code" => 'Тип операции (приход, расход, перемещение)',
	        "adesk_bank_name" => "Наименование банка",
	        "adesk_company_name" => "Название компании",
	        "adesk_description" => "Основание операции",
	        "adesk_cfs_category_name" => "Статья ОДДС",
	        "adesk_contractor_name" => "Контрагент",
	        "adesk_project_name" => "Проект",
	        "bank_account_id" => 'Банк',
	        "agreement_id" => 'Договор',
	        "VAT" => 'Ставка НДС',
	        "project_id" => 'Проект',
	        "cfs_item_id" => 'Статья ОДДС',
            "has_accrual" => 'Требуется ли генерить обязательство',
	        "accrual_date_offset" => 'Дата начисления (относительно проводки)',
	        "pl_item_id" => 'Статья ОФР',
            'accrual_description' => 'Основание для начисления',

        ];
    }
    

}

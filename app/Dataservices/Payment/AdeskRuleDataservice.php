<?php


namespace App\Dataservices\Payment;

use App\Models\Agreement;
use App\Models\Project;
use App\Models\BankAccount;
use App\Models\CFSItem;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use App\Http\Requests\Payment\AdeskRuleRequest;
use App\Models\AdeskRule;
use App\Models\Impex\ImportAdeskOperation;
use App\Models\PlItem;
use PhpParser\Error;

class AdeskRuleDataservice
{   
    /**
     * получение правил про условию
     */
    public static function getData(string $searchStr, int $perPage = 15): LengthAwarePaginator
    {
        // Приведение строки к нижнему регистру и добавление подстановочного знака для поиска
        $searchStr = '%' . preg_replace('/\s+/', '%', mb_strtolower($searchStr)) . '%';
        // Выполнение запроса с условием поиска по нескольким полям и пагинацией
        return AdeskRule::query()
            ->whereRaw('LOWER(name) LIKE ?', [$searchStr])
            ->orderBy('name')
            ->paginate($perPage);
    }

    /**
     * получение правил в зависимости от условий
     */
    public static function index(Request $request): array
    {

        $filter = $request->get('searchStr')??'';
        $rules = self::getData($filter);

        return [
            'adeskRules' => $rules,
            'filter' => $filter,
        ];
    }

    /**
     *снабжение данными форму редактирования платежа
     */
    public static function providePaymentEditor(AdeskRule $rule): array
    {
            return [
            'model' => $rule,
            'accounts' => BankAccount::orderBy('account_number')->get(),
            'agreements' => Agreement::orderBy('agr_number')->get(),
            'projects' => Project::query()->orderBy('name')->get(),
            'cfsItems' => CFSItem::orderBy('name')->get(),
            'plItems' => PlItem::orderBy('name')->get(),
            
        ];
    }

    public static function create(Request $request, ImportAdeskOperation $operation = null): AdeskRule
    {
        $rule = new AdeskRule();
        if ($operation) {
            $rule->fill([
                    'adesk_type_operation_code' => $operation->adesk_type_operation_code,
                    'adesk_bank_name' => $operation->adesk_bank_name,
                    'adesk_company_name' => $operation->adesk_company_name,
                    'adesk_description' => $operation->description,
                    'adesk_cfs_category_name' => $operation->adesk_cfs_category_name,
                    'adesk_contractor_name' => $operation->adesk_contractor_name,
                    'adesk_project_name' => $operation->adesk_project_name,
                    'bank_account_id' => $operation->bank_account_id,
                    'agreement_id' => $operation->agreement_id,
                    'VAT' => $operation->VAT,
                    'project_id' => $operation->project_id,
                    'cfs_item_id' => $operation->cfs_item_id,
                    'has_accrual' => $operation->has_accrual,
                    'accrual_date_offset' => 0,
                    'pl_item_id' => $operation->pl_item_id,
                    'accrual_description'=> $operation->accrual_description,
            ]);
        };
        if (!empty($request->old())) $rule->fill($request->old());
        return $rule;
    }

    public static function edit(Request $request, AdeskRule $rule)
    {
        if (!empty($request->old())) $rule->fill($request->old());
    }


    public static function saveChanges(AdeskRuleRequest $request, AdeskRule $rule)
    {
        $rule->project_id = ($rule->project_id != "*БЕЗ ПРОЕКТА*")?$rule->project_id:null;
        if ($rule->id) 
            {$rule->fill($request->all());}
        else 
            $rule->fill($request->except('id'));

        // if (!$payment->created_by) $payment->created_by = Auth::user()->id;
        if ($rule->id) $rule->updated_at = now();
        else $rule->created_at = now();
        $rule->save();
    }

    public static function store(AdeskRuleRequest $request)
    {
        try {
            $rule = new AdeskRule();
            self::saveChanges($request, $rule);
            session()->flash('message', 'Добавлено новое правило для импорта операций Adesk');
        } catch (Error $err) {
            session()->flash('error', 'Не удалось добавить новое правило для импорта операций Adesk');
        }

    }

    public static function update(AdeskRuleRequest $request, AdeskRule $rule)
    {
        try {
            self::saveChanges($request, $rule);
            session()->flash('message', 'Данные о правиле для импорта операций Adesk обновлены');
        } catch (Error $err) {
            session()->flash('error', 'Не удалось обновить данные о правиле для импорта операций Adesk');
        }
    }
   

    public static function delete(AdeskRule $rule)
    {
        
        try {
            $rule->delete();
            session()->flash('message', 'Правило для импорта операций Adesk удалено');
        } catch (Error $err) {
            session()->flash('error', 'Не удалось удалить правило для импорта операций Adesk');
        }
    }    
}

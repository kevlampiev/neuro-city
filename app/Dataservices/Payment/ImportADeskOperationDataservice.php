<?php


namespace App\Dataservices\Payment;

use App\Models\Agreement;
use App\Http\Requests\Payment\PaymentRequest;
use App\Models\Payment;
use App\Models\Project;
use App\Models\BankAccount;
use App\Models\CFSItem;
use App\Models\Company;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\Impex\ImportAdeskOperation;
use Illuminate\Http\Request;
use App\Http\Requests\Payment\ImportAdeskOperatinRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\PaymentParty;
use Carbon\Carbon;
use PhpParser\Error;

class ImportADeskOperationDataservice
{   
    /**
     * получение платежей по куче условий
     */
    public static function getData(string $searchStr, string $filterDateStart = null, string $filterDateEnd = null, int $perPage = 15): LengthAwarePaginator
    {
        // Приведение строки к нижнему регистру и добавление подстановочного знака для поиска
        $searchStr = '%' . preg_replace('/\s+/', '%', mb_strtolower($searchStr)) . '%';
    
        // Установка значений по умолчанию для диапазона дат
        $dateStart = $filterDateStart ? Carbon::parse($filterDateStart) : Carbon::parse("2020-01-01");
        $dateEnd = $filterDateEnd ? Carbon::parse($filterDateEnd) : Carbon::parse("2099-12-31");
    
        // Проверка на корректность диапазона
        if ($dateEnd < $dateStart) {
            $dateEnd = $dateStart;
        }
    
        // Запрос с фильтрацией по нескольким полям и диапазону дат
        return ImportAdeskOperation::query()
            ->where(function($query) use ($searchStr) {
                $query->whereRaw('LOWER(adesk_bank_name) LIKE ?', [$searchStr])
                    ->orWhereRaw('LOWER(adesk_company_name) LIKE ?', [$searchStr])
                    ->orWhereRaw('LOWER(description) LIKE ?', [$searchStr])
                    ->orWhereRaw('LOWER(adesk_cfs_category_name) LIKE ?', [$searchStr])
                    ->orWhereRaw('LOWER(adesk_contractor_name) LIKE ?', [$searchStr]);
            })
            ->whereBetween('date_open', [$dateStart->format('Y-m-d'), $dateEnd->format('Y-m-d')])
            ->orderByDesc('date_open')
            ->orderBy('amount')
            ->paginate($perPage); 
    }
    
    /**
     * Получение платежей в зависимости от условий
     */
    public static function index(Request $request): array
    {
        $filter = $request->get('searchStr', '');
        $filterDateStart = $request->get('filterDateStart', '');
        $filterDateEnd = $request->get('filterDateEnd', '');
    
        $adeskPayments = self::getData($filter, $filterDateStart, $filterDateEnd);
    
        return [
            'adeskPayments' => $adeskPayments,
            'filter' => $filter,
            'filterDateStart' => $filterDateStart,
            'filterDateEnd' => $filterDateEnd,
        ];
    }

    /**
     *снабжение данными форму редактирования платежа
     */
    public static function providePaymentEditor(ImportAdeskOperation $payment): array
    {
            return [
            'model' => $payment,
            'accounts' => BankAccount::orderBy('account_number')->get(),
            'agreements' => Agreement::orderBy('agr_number')->get(),
            'projects' => Project::query()->orderBy('name')->get(),
            'cfsItems' => CFSItem::orderBy('name')->get(),
            'beneficiaries' => Company::orderBy('name')->get(),
        ];
    }

    // public static function create(Request $request): Payment
    // {
    //     $payment = new Payment();
    //     if (!empty($request->old())) $payment->fill($request->old());
    //     return $payment;
    // }

    public static function edit(Request $request, ImportAdeskOperation $payment)
    {
        if (!empty($request->old())) $payment->fill($request->old());
    }


    public static function saveChanges(ImportAdeskOperatinRequest $request, ImportAdeskOperation $payment)
    {
        $payment->fill($request->all());
        $payment->save();
    }

    public static function store(ImportAdeskOperatinRequest $request)
    {
        try {
            $payment = new ImportAdeskOperation();
            self::saveChanges($request, $payment);
            session()->flash('message', 'Добавлен новый платеж');
        } catch (Error $err) {
            session()->flash('error', 'Не удалось добавить новый платеж');
        }

    }

    public static function update(ImportAdeskOperatinRequest $request, ImportAdeskOperation $payment)
    {
        try {
            self::saveChanges($request, $payment);
            session()->flash('message', 'Данные платежа обновлены');
        } catch (Error $err) {
            session()->flash('error', 'Не удалось обновить данные платежа');
        }
    }
   

    public static function delete(Payment $payment)
    {
        
        try {
            $payment->delete();
            session()->flash('message', 'Даннные о проводке удалены');
        } catch (Error $err) {
            session()->flash('error', 'Не удалось удалить данные о проводке');
        }
    }    
}
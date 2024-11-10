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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Models\PaymentParty;
use App\Models\VPaymentExtended;
use PhpParser\Error;

class PaymentDataservice
{   
    /**
     * получение платежей по куче условий
     */
    public static function getData(string $searchStr, string $filterDateStart = null, string $filterDateEnd = null, int $perPage = 15): LengthAwarePaginator
    {

        // Приведение строки к нижнему регистру и добавление подстановочного знака для поиска
        $searchStr = '%' . preg_replace('/\s+/', '%', mb_strtolower($searchStr)) . '%';

        // Установка значений по умолчанию для диапазона дат
        $dateStart = $filterDateStart ? Carbon::parse($filterDateStart) : Carbon::parse("2000-01-01");
        $dateEnd = $filterDateEnd ? Carbon::parse($filterDateEnd) : Carbon::parse("2099-12-31");
    
        // Проверка на корректность диапазона
        if ($dateEnd < $dateStart) {
            $dateEnd = $dateStart;
        }

        // Выполнение запроса с условием поиска по нескольким полям и пагинацией
        return VPaymentExtended::query()
            ->where(function($query) use ($searchStr) {
                // Условие на строку поиска
                $query->whereRaw('LOWER(account_name) LIKE ?', [$searchStr])
                    ->orWhereRaw('LOWER(description) LIKE ?', [$searchStr])
                    ->orWhereRaw('LOWER(agreement_name) LIKE ?', [$searchStr])
                    ->orWhereRaw('LOWER(seller_name) LIKE ?', [$searchStr])
                    ->orWhereRaw('LOWER(buyer_name) LIKE ?', [$searchStr]);
            })
            ->whereBetween('date_open', [$dateStart->format('Y-m-d'), $dateEnd->format('Y-m-d')])
            ->orderByDesc('date_open')
            ->orderBy('account_name')
            ->paginate($perPage);
    }

    /**
     * получение платежей в зависимости от условий
     */
    public static function index(Request $request): array
    {

        $filter = $request->get('searchStr')??'';
        $filterDateStart = $request->get('filterDateStart')??'';
        $filterDateEnd = $request->get('filterDateEnd')??'';

        $payments = self::getData($filter, $filterDateStart, $filterDateEnd);

        return [
            'payments' => $payments,
            'filter' => $filter,
            'filterDateStart' => $filterDateStart,
            'filterDateEnd' => $filterDateEnd,
        ];
    }

    /**
     *снабжение данными форму редактирования платежа
     */
    public static function providePaymentEditor(Payment $payment): array
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

    public static function create(Request $request, Agreement $agreement): Payment
    {
        $payment = new Payment();
        if ($agreement) $payment->agreement_id = $agreement->id;
        if (!empty($request->old())) $payment->fill($request->old());
        return $payment;
    }

    public static function edit(Request $request, Payment $payment)
    {
        if (!empty($request->old())) $payment->fill($request->old());
    }


    public static function saveChanges(PaymentRequest $request, Payment $payment)
    {
        if ($payment->id) 
            {$payment->fill($request->all());}
        else 
            $payment->fill($request->except('id'));

        // if (!$payment->created_by) $payment->created_by = Auth::user()->id;
        if ($payment->id) $payment->updated_at = now();
        else $payment->created_at = now();
        $payment->save();
    }

    public static function store(PaymentRequest $request)
    {
        try {
            $payment = new Payment();
            self::saveChanges($request, $payment);
            session()->flash('message', 'Добавлен новый платеж');
        } catch (Error $err) {
            session()->flash('error', 'Не удалось добавить новый платеж');
        }

    }

    public static function update(PaymentRequest $request, Payment $payment)
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

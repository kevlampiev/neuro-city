<?php


namespace App\Dataservices\Payment;

use App\Models\Agreement;
use App\Http\Requests\Payment\PaymentRequest;
use App\Models\Payment;
use App\Models\Project;
use App\Models\BankAccount;
use App\Models\CFSItem;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PaymentParty;
use PhpParser\Error;

class PaymentDataservice
{   
    /**
     * получение платежей по куче условий
     */
    public static function getData(string $search_str, int $perPage = 15): LengthAwarePaginator
    {

        // Приведение строки к нижнему регистру и добавление подстановочного знака для поиска
        $searchStr = '%' . preg_replace('/\s+/', '%', mb_strtolower($search_str)) . '%';

        // Выполнение запроса с условием поиска по нескольким полям и пагинацией
        return PaymentParty::query()
            ->whereRaw('LOWER(company_name) LIKE ?', [$searchStr])
            ->orWhereRaw('LOWER(bank_name) LIKE ?', [$searchStr])
            ->orWhereRaw('LOWER(description) LIKE ?', [$searchStr])
            ->orWhereRaw('LOWER(agreement_name) LIKE ?', [$searchStr])
            ->paginate($perPage); // Возвращаем результат с пагинацией
    }

    /**
     * получение платежей в зависимости от условий
     */
    public static function index(Request $request): array
    {

        $filter = ($request->get('searchStr')) ?? '';

        $payments = self::getData($filter);

        return [
            'payments' => $payments,
            'filter' => $filter,
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
        ];
    }

    public static function create(Request $request): Payment
    {
        $payment = new Payment();
        if (!empty($request->old())) $payment->fill($request->old());
        return $payment;
    }

    public static function edit(Request $request, Payment $payment)
    {
        if (!empty($request->old())) $payment->fill($request->old());
    }


// Я ТУТ!!!!!!!!!!!!!!!!!!!!

    public static function saveChanges(PaymentRequest $request, Payment $payment)
    {
        $payment->fill($request->all());
        if (!$payment->created_by) $payment->created_by = Auth::user()->id;
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
   
}

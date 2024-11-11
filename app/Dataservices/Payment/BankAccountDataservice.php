<?php


namespace App\Dataservices\Payment;


use App\Models\BankAccount;
use Illuminate\Http\Request;
use App\Http\Requests\Payment\BankAccountRequest;
use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Error;
use Illuminate\Support\Carbon as SupportCarbon;

class BankAccountDataservice
{

    // public static function getAll()
    // {
    //     return BankAccount::query()->orderBy('account_number')->paginate(15);
    // }

    public static function getFiltered(string $filter)
{
    $searchStr = '%' . preg_replace('/\s+/', '%', mb_strtolower($filter ?? "")) . '%';

    return BankAccount::query()
        ->join('companies as owners', 'bank_accounts.owner_id', '=', 'owners.id') // Присоединение таблицы владельцев
        ->join('companies as banks', 'bank_accounts.bank_id', '=', 'banks.id') // Присоединение таблицы банков
        ->where(function($query) use ($searchStr) {
            $query->whereRaw('LOWER(bank_accounts.description) like ?', [$searchStr]) // Фильтр по описанию счета
                  ->orWhereRaw('LOWER(owners.name) like ?', [$searchStr]) // Фильтр по имени владельца
                  ->orWhereRaw('LOWER(banks.name) like ?', [$searchStr]); // Фильтр по имени банка
        })
        ->select('bank_accounts.*', 'owners.name as owner_name', 'banks.name as bank_name') // Выбор полей
        ->orderBy('owner_name') // Сортировка по имени владельца
        ->orderBy('bank_name')  // Сортировка по имени банка
        ->paginate(15);
}


    public static function index(Request $request): array
    {
        $filter = ($request->get('searchStr')) ?? '';
        $accounts = self::getFiltered($filter);
        return ['accounts' => $accounts, 'filter' => $filter];
    }


    public static function provideBankAccountEditor(BankAccount $bankAccount):array
    {
        $banks = Company::where('company_type', 'bank')->orderBy('name')->get();
        $companies = Company::where('our_company', true)->orderBy('name')->get();
        return [
            'model' => $bankAccount,
            'owners' => $companies,
            'banks' => $banks,
        ];
    }

    public static function create(Request $request, $company): BankAccount
    {
        $bankAccount = new BankAccount();
        $bankAccount->owner_id= $company;
        $bankAccount->date_open = Carbon::now()->format('Y-m-d');
        if (!empty($request->old())) $bankAccount->fill($request->old());
        return $bankAccount;
    }

    public static function edit(Request $request, BankAccount $BankAccount)
    {

        if (!empty($request->old())) {
            $BankAccount->fill($request->old());
        };
    }

    public static function saveChanges(BankAccountRequest $request, BankAccount $BankAccount)
    {
        
        $BankAccount->fill($request->except('id'));
        if (!$BankAccount->created_by) $BankAccount->created_by = Auth::user()->id;
        if ($BankAccount->id) $BankAccount->updated_at = now();
        else $BankAccount->created_at = now();
        $BankAccount->save();
    }

    public static function store(BankAccountRequest $request)
    {
        try {
            $bankAccount = new BankAccount();
            self::saveChanges($request, $bankAccount);
            session()->flash('message', 'Добавлен новый договор');
        } catch (Error $err) {
            session()->flash('error', 'Не удалось добавить новый договор');
        }

    }

    public static function update(BankAccountRequest $request, BankAccount $BankAccount)
    {
        try {
            self::saveChanges($request, $BankAccount);
            session()->flash('message', 'Данные договора обновлены');
        } catch (Error $err) {
            session()->flash('error', 'Не удалось обновить данные договора');
        }
    }

    public static function delete(BankAccount $bankAccount)
    {
        
        try {
            $bankAccount->delete();
            session()->flash('message', 'Банковский счет удален');
        } catch (Error $err) {
            session()->flash('error', 'Не удалось удалить банковский счет');
        }
    }    


}

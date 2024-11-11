<?php


namespace App\Dataservices\Accrual;

use App\Models\Agreement;
use App\Http\Requests\Accrual\AccrualRequest;
use App\Models\Accrual;
use App\Models\Project;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\PlItem;
use App\Models\VAccrualExtended;
use PhpParser\Error;
use Illuminate\Support\Facades\Auth;

class AccrualDataservice
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
        return VAccrualExtended::query()
            ->where(function($query) use ($searchStr) {
                // Условие на строку поиска
                $query->whereRaw('LOWER(project_name) LIKE ?', [$searchStr])
                    ->orWhereRaw('LOWER(description) LIKE ?', [$searchStr])
                    ->orWhereRaw('LOWER(agreement_name) LIKE ?', [$searchStr])
                    ->orWhereRaw('LOWER(seller_name) LIKE ?', [$searchStr])
                    ->orWhereRaw('LOWER(pl_name) LIKE ?', [$searchStr])
                    ->orWhereRaw('LOWER(buyer_name) LIKE ?', [$searchStr]);
            })
            ->whereBetween('date_open', [$dateStart->format('Y-m-d'), $dateEnd->format('Y-m-d')])
            ->orderByDesc('date_open')
            ->orderByDesc('amount')
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

        $accruals = self::getData($filter, $filterDateStart, $filterDateEnd);

        return [
            'accruals' => $accruals,
            'filter' => $filter,
            'filterDateStart' => $filterDateStart,
            'filterDateEnd' => $filterDateEnd,
        ];
    }

    /**
     *снабжение данными форму редактирования платежа
     */
    public static function provideAccrualEditor(Accrual $accrual): array
    {
            return [
            'model' => $accrual,
            'agreements' => Agreement::orderBy('agr_number')->get(),
            'projects' => Project::query()->orderBy('name')->get(),
            'plItems' => PlItem::orderBy('name')->get(),
        ];
    }

    public static function create(Request $request, Agreement $agreement): Accrual
    {
        $accrual = new Accrual();
        if ($agreement) $accrual->agreement_id = $agreement->id;
        $accrual->date_open = Carbon::now()->format('Y-m-d');
        if (!empty($request->old())) $accrual->fill($request->old());
        return $accrual;
    }

    public static function edit(Request $request, Accrual $accrual)
    {
        if (!empty($request->old())) $accrual->fill($request->old());
    }


    public static function saveChanges(AccrualRequest $request, Accrual $accrual)
    {
        $accrual->project_id = ($accrual->project_id != "*БЕЗ ПРОЕКТА*")?$accrual->project_id:null;
        if ($accrual->id) {
            $accrual->fill($request->all());
            $accrual->updated_at = now();
        } else {
            $accrual->fill($request->except('id'));
            $accrual->created_at = now();
            $accrual->created_by = Auth::user()->id;
            };

        // if (!$accrual->created_by) $accrual->created_by = Auth::user()->id;
        if ($accrual->id) $accrual->updated_at = now();
        else $accrual->created_at = now();
        $accrual->save();
    }

    public static function store(AccrualRequest $request)
    {
        try {
            $accrual = new Accrual();
            self::saveChanges($request, $accrual);
            session()->flash('message', 'Добавлено новое начисление');
        } catch (Error $err) {
            session()->flash('error', 'Не удалось добавить новое начисление');
        }

    }

    public static function update(AccrualRequest $request, Accrual $accrual)
    {
        try {
            self::saveChanges($request, $accrual);
            session()->flash('message', 'Данные платежа обновлены');
        } catch (Error $err) {
            session()->flash('error', 'Не удалось обновить данные платежа');
        }
    }
   

    public static function delete(Accrual $accrual)
    {
        
        try {
            $accrual->delete();
            session()->flash('message', 'Даннные о начислении удалены');
        } catch (Error $err) {
            session()->flash('error', 'Не удалось удалить данные о начислении');
        }
    }    
}

<?php


namespace App\Dataservices\Accrual;

use App\Models\Agreement;
use App\Http\Requests\Accrual\PlanAccrualRequest;
use App\Models\Project;
use App\Models\PlItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PlanAccrual;
use PhpParser\Error;

class PlanAccrualDataservice
{   
    /**
     *снабжение данными форму редактирования платежа
     */
    public static function providePaymentEditor(PlanAccrual $accrual): array
    {
            return [
            'planAccrual' => $accrual,
            'agreements' => Agreement::orderBy('agr_number')->get(),
            'projects' => Project::query()->orderBy('name')->get(),
            'products' => Product::orderByDesc('is_service')->orderBy('name')->get(),
            'plItems' => PlItem::orderBy('name')->get(),
        ];
    }

    public static function create(Request $request, Agreement $agreement): PlanAccrual
    {
        $accrual = new PlanAccrual();
        if ($agreement) $accrual->agreement_id = $agreement->id;
        if (!empty($request->old())) $accrual->fill($request->old());
        return $accrual;
    }

    public static function edit(Request $request, PlanAccrual $accrual)
    {
        if (!empty($request->old())) $accrual->fill($request->old());
    }


    public static function saveChanges(PlanAccrualRequest $request, PlanAccrual $accrual)
    {
        $accrual->project_id = ($accrual->project_id != "*БЕЗ ПРОЕКТА*")?$accrual->project_id:null;
        if ($accrual->id) {
            $accrual->fill($request->all());
            $accrual->updated_at = now();
        } else {
            $accrual->fill($request->except('id'));
            $accrual->created_by = Auth::id();
            $accrual->created_at = now();
        }    
        
        $accrual->save();
    }

    public static function store(PlanAccrualRequest $request)
    {
        try {
            $accrual = new PlanAccrual();
            self::saveChanges($request, $accrual);
            session()->flash('message', 'Добавлен новое плановое начисление по договору');
        } catch (Error $err) {
            session()->flash('error', 'Не удалось добавить новое нлановое начисление по договору');
        }

    }

    public static function update(PlanAccrualRequest $request, PlanAccrual $accrual)
    {
        try {
            self::saveChanges($request, $accrual);
            session()->flash('message', 'Данные начисления обновлены');
        } catch (Error $err) {
            session()->flash('error', 'Не удалось обновить данные начисления');
        }
    }
   

    public static function delete(PlanAccrual $accrual)
    {
        
        try {
            $accrual->delete();
            session()->flash('message', 'Даннные о плановом начислении удалены');
        } catch (Error $err) {
            session()->flash('error', 'Не удалось удалить данные о плановом начислении');
        }
    }    
}

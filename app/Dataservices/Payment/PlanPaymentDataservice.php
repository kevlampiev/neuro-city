<?php


namespace App\Dataservices\Payment;

use App\Models\Agreement;
use App\Http\Requests\Payment\PlanPaymentRequest;
use App\Models\Project;
use App\Models\CFSItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PlanPayment;
use PhpParser\Error;

class PlanPaymentDataservice
{   
    /**
     *снабжение данными форму редактирования платежа
     */
    public static function providePaymentEditor(PlanPayment $payment): array
    {
            return [
            'planPayment' => $payment,
            'agreements' => Agreement::orderBy('agr_number')->get(),
            'projects' => Project::query()->orderBy('name')->get(),
            'cfsItems' => CFSItem::orderBy('name')->get(),
        ];
    }

    public static function create(Request $request, Agreement $agreement): PlanPayment
    {
        $payment = new PlanPayment();
        if ($agreement) $payment->agreement_id = $agreement->id;
        if (!empty($request->old())) $payment->fill($request->old());
        return $payment;
    }

    public static function edit(Request $request, PlanPayment $payment)
    {
        if (!empty($request->old())) $payment->fill($request->old());
    }


    public static function saveChanges(PlanPaymentRequest $request, PlanPayment $payment)
    {
        $payment->project_id = ($payment->project_id != "*БЕЗ ПРОЕКТА*")?$payment->project_id:null;
        if ($payment->id) {
            $payment->fill($request->all());
            $payment->updated_at = now();
        } else {
            $payment->fill($request->except('id'));
            $payment->created_by = Auth::id();
            $payment->created_at = now();
        }    
        
        $payment->save();
    }

    public static function store(PlanPaymentRequest $request)
    {
        try {
            $payment = new PlanPayment();
            self::saveChanges($request, $payment);
            session()->flash('message', 'Добавлен новый плановый платеж по договору');
        } catch (Error $err) {
            session()->flash('error', 'Не удалось добавить новый плановый платеж по договору');
        }

    }

    public static function update(PlanPaymentRequest $request, PlanPayment $payment)
    {
        try {
            self::saveChanges($request, $payment);
            session()->flash('message', 'Данные платежа обновлены');
        } catch (Error $err) {
            session()->flash('error', 'Не удалось обновить данные платежа');
        }
    }
   

    public static function delete(PlanPayment $payment)
    {
        
        try {
            $payment->delete();
            session()->flash('message', 'Даннные о плановом платеже удалены');
        } catch (Error $err) {
            session()->flash('error', 'Не удалось удалить данные о плановом платеже');
        }
    }    
}

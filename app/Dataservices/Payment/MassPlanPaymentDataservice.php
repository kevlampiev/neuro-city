<?php


namespace App\Dataservices\Payment;

use App\Http\Requests\Payment\AddMassPlanPaymentRequest;
use App\Models\Agreement;
use App\Models\Project;
use App\Models\CFSItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PlanPayment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PhpParser\Error;

class MassPlanPaymentDataservice
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
            'repeat_count'=>10,
        ];
    }

    public static function create(Request $request, Agreement $agreement): PlanPayment
    {
        $payment = new PlanPayment();
        if ($agreement) $payment->agreement_id = $agreement->id;
        $payment->repeat_count = 10;
        if (!empty($request->old())) $payment->fill($request->old());
        return $payment;
    }
   


    public static function saveSingle(AddMassPlanPaymentRequest $request, int $period)
    {
        $payment= new PlanPayment();
        
            $payment->fill($request->except('id','repeat_count', 'initial_date', 'shifted_date'));
            $payment->initial_date = Carbon::parse($request->input('initial_date'))->addMonth($period);
            $payment->shifted_date = Carbon::parse($request->input('shifted_date'))->addMonth($period);
            $payment->project_id = ($payment->project_id != "*БЕЗ ПРОЕКТА*")?$payment->project_id:null;
            $payment->created_by = Auth::id();
            $payment->created_at = now();
        
        $payment->save();
    }

    public static function store(AddMassPlanPaymentRequest $request)
    {
        DB::beginTransaction();
        try {
            $repeat_count=(int) $request->input('repeat_count');
            for ($i=0; $i<$repeat_count; $i++) {
                self::saveSingle($request, $i);
            }
            DB::commit();
            session()->flash('message', 'Добавлена серия плановых платежей по договору');
        } catch (Error $err) {
            DB::rollBack();
            session()->flash('error', 'Не удалось добавить серию плановых платежей по договору');
        }

    }
 
}

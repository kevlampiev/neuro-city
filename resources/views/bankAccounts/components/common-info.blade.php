<table>

    <tr>
        <td class="text-right text-black-50">Номер счета</td>
        <td class="text-left p-2">{{$model->account_number}}</td>
    </tr>
    <tr>
        <td class="text-right text-black-50">Вледелец счета</td>
        <td class="text-left p-2"> {{$model->owner->name}}</td>
    </tr>
    
    <tr>
        <td class="text-right text-black-50">Банк</td>
        <td class="text-left p-2"> {{$model->bank->name}}</td>
    </tr>

    <tr>
        <td class="text-right text-black-50">Дата открытия</td>
        <td class="text-left p-2"> {{\Carbon\Carbon::parse($model->date_open)->format('d.m.Y')}}</td>
    </tr>

    <tr>
        @if($model->date_close)
            <td class="text-right text-black-50">Счет закрыт</td>
            <td class="text-left p-2"> Дата закрытия: {{\Carbon\Carbon::parse($model->date_close)->format('d.m.Y')}}</td>
        @else 
           <td class="text-right text-black-50">Статус счета</td>
            <td class="text-left p-2"> Счет действующий </td>
        @endif
    </tr>


    <tr>
        <td class="text-right text-black-50">Описание</td>
        <td class="text-left p-2"> {{$model->description}}</td>
    </tr>

    <tr>
        <td class="text-right text-black-50"></td>
        <td>
            @if(Gate::allows('e-accounts'))
                <a href="{{route('accounts.edit',['bankAccount'=>$model->id])}}"
                   class="btn btn-outline-secondary" role="button" aria-pressed="true">Отредактировать</a>
            @endif
        </td>
    </tr>


</table>

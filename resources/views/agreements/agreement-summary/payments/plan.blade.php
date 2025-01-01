    <div class="row">
        <div class="col-md-12">
            <a class="btn btn-outline-info m-2"
               href="{{route('plan-payments.add', ['agreement'=>$agreement])}}">
               Новый платеж
            </a>
            <a class="btn btn-outline-info m-2"
               href="{{route('plan-payments.mass-add', ['agreement'=>$agreement])}}">
               Добавить серию платежей
            </a>   
        </div>
    </div>


    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Дата платежа</th>
                <th scope="col">Сумма с НДС</th>
                <th scope="col">Проект</th>
                <th scope="col">Статья ОДДС</th>
                <th scope="col"></th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
        @forelse($agreement->planPayments as $payment)
            <tr>
                <th scope="row">{{$loop->index+1}}</th>
                <td>
                    @if(!$payment->shifted_date||$payment->initial_date==$payment->shifted_date)
                        {{\Carbon\Carbon::parse($payment->initial_date)->format('d.m.Y')}}
                    @else
                        {{\Carbon\Carbon::parse($payment->initial_date)->format('d.m.Y')}} &rarr;
                        {{\Carbon\Carbon::parse($payment->shifted_date)->format('d.m.Y')}}
                    @endif    
                </td>
                <td class="text-right">
                    {!! str_replace(' ', '&nbsp;', number_format($payment->amount, 2, ',', ' ')) !!}
                    @if($payment->VAT&&$payment->VAT!=0)
                        <span class="text-secondary lower"> в т.ч. НДС {!! str_replace(' ', '&nbsp;', number_format($payment->VAT, 2, ',', ' ')) !!}  </span>
                    @endif
                </td>
                
                <td class="text-left">
                    {{$payment->project?$payment->project->name:''}}
                </td>
                <td class="text-left">
                    {{$payment->cfsItem->name}}
                </td>
                

                <td>
                    @if(Gate::allows('e-payments'))
                    <a href="{{route('plan-payments.edit', ['payment' => $payment])}}">
                        &#9998;Изменить </a>
                    @endif    
                </td>
                <td>
                    @if(Gate::allows('e-payments'))
                    <a href="{{route('plan-payments.destroy', ['payment' => $payment])}}"
                       onclick="return confirm('Действительно удалить данные о платеже?')">
                        &#10008;Удалить </a>
                    @endif    
                </td>
            </tr>
        @empty
            <tr>
                <th colspan="6">Нет записей</th>
            </tr>
        @endforelse
        <tr>
            <th colspan="2">Всего</th>
            <th class="text-right">{{number_format($agreement->planPayments->sum('amount'), 2)}}</th>
            <th class="text-left"></th>
            <th></th>
            <th></th>
        </tr>
        </tbody>
    </table>


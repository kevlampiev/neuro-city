    <div class="row">
        <div class="col-md-12">
            <a class="btn btn-outline-info m-2"
               href="{{route('plan-payments.add', ['agreement'=>$agreement])}}">
               <i class="bi bi-cash"></i>
               Новый платеж
            </a>
            <a class="btn btn-outline-info m-2"
               href="{{route('plan-payments.mass-add', ['agreement'=>$agreement])}}">
               <i class="bi bi-cash-stack"></i>
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
                <th scope="col">Статья, проект</th>
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
                    {{$payment->cfsItem->name}} {{$payment->project?', '.$payment->project->name:''}}
                </td>
                

                <td>
                    @if(Gate::allows('e-payments'))
                        <a href="{{ route('plan-payments.edit', ['payment' => $payment]) }}"
                        class="text-decoration-none text-primary p-1"
                        data-bs-toggle="tooltip"
                        title="Отредактировать запись"
                        style="transition: background-color 0.3s;"
                        onmouseover="this.style.backgroundColor='#f8f9fa';"
                        onmouseout="this.style.backgroundColor='';">
                            &#9998; 
                        </a>
                    @endif    
                </td>
                <td>
                    @if(Gate::allows('e-payments'))
                        <a href="{{ route('plan-payments.destroy', ['payment' => $payment]) }}"
                        class="text-decoration-none text-danger p-1"
                        data-bs-toggle="tooltip"
                        title="Удалить запись"
                        style="transition: background-color 0.3s;"
                        onclick="return confirm('Действительно удалить данные о платеже?');"
                        onmouseover="this.style.backgroundColor='#f8f9fa';"
                        onmouseout="this.style.backgroundColor='';">
                            &#10008;
                        </a>
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
            <th class="text-right" colspan="4">
                {!! str_replace(' ', '&nbsp;', number_format($agreement->planPayments->sum('amount'), 2, ',', ' ')) !!}
                <span class="text-secondary">
                    в т.ч. НДС 
                    {!! str_replace(' ', '&nbsp;', number_format($agreement->planPayments->sum('VAT'), 2, ',', ' ')) !!}
                </span>    
            </th>
            <th class="text-left"></th>
            <th></th>
            <th></th>
        </tr>
        </tbody>
    </table>


@if(Gate::allows('e-payments'))
<div class="row">
    <div class="col-md-12">
        <a class="btn btn-outline-info m-2"
            href="{{route('payments.create', ['agreement'=>$agreement])}}">
            <i class="bi bi-cash-coin"></i>
            Добавить фактический платеж
        </a>
    </div>
</div>
@endif

<table class="table table-hover">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">Дата</th>      
        <th scope="col">Сумма</th>
        <th scope="col">Банк</th>
        <th scope="col">Статья, Проект</th>
        <th scope="col">Основание</th>
        {{-- <th scope="col">Валюта</th> --}}
        <th scope="col"></th>
        <th scope="col"></th>
    </tr>
    </thead>
    <tbody>
    @forelse($agreement->payments as $payment)
        <tr>
            {{-- @dd($payment); --}}
            <th scope="row">{{$loop->index+1}}</th>
            <td>{{\Carbon\Carbon::parse($payment->date_open)->format('d.m.Y')}}</td>
            <td class="text-right">
                {!! str_replace(' ', '&nbsp;', number_format($payment->amount, 2, ',', ' ')) !!}
                @if($payment->VAT&&$payment->VAT!=0)
                    <span class="text-secondary lower"> в т.ч. НДС {!! str_replace(' ', '&nbsp;', number_format($payment->VAT, 2, ',', ' ')) !!}  </span>
                @endif
            </td>
            <td>{{$payment->account_name}}</td>
            <td class="text-left">
                {{$payment->cfs_item_name}}
                @if($payment->project_name)
                    , {{$payment->project_name}}
                @endif
            </td>
            <td class="text-left">
                {{ implode(' ', array_slice(explode(' ', $payment->description), 0, 7)) }}{{ str_word_count($payment->description) > 10 ? '...' : '' }} ...
            </td>
            <td>
                @if(Gate::allows('e-payments'))
                        <a href="{{route('payments.edit', ['payment' => $payment])}}"
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
                    <form action="{{ route('payments.destroy', ['payment' => $payment]) }}" method="POST" style="display: inline;" 
                        onsubmit="return confirm('Действительно удалить данные о платеже?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background: none; border: none; color: red; cursor: pointer;">
                            &#10008;
                        </button>
                    </form>
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
            {!! str_replace(' ', '&nbsp;', number_format($agreement->payments->sum('amount'), 2, ',', ' ')) !!}
            <span class="text-secondary"> в т.ч. НДС {!! str_replace(' ', '&nbsp;', number_format($agreement->payments->sum('VAT'), 2, ',', ' ')) !!} </span>
        </th>
        <th class="text-left"></th>
        <th></th>
        <th></th>
    </tr>
    </tbody>
</table>



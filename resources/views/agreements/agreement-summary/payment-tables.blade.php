<div class="col-md-12 p-4">
    <h4>Фактические платежи по договору</h4>
    @if(Gate::allows('e-payments'))
    <div class="row">
        <div class="col-md-12">
            <a class="btn btn-outline-info mr-2"
               href="{{route('payments.create', ['agreement'=>$agreement])}}">Новый платеж</a>
            {{-- <a class="btn btn-outline-info mr-2"
               href="{{route('admin.massAddPayments', ['agreement'=>$agreement])}}">Добавить серию платежей</a> --}}
        </div>
    </div>
    @endif

    <table class="table table-hover">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Дата</th>
            <th scope="col">Сумма</th>
            <th scope="col">Валюта</th>
            <th scope="col">Состояние</th>
            <th scope="col"></th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
        @php
            $totalPayed = $agreement->payments->sum('amount');
        @endphp
        @forelse($payments as $payment)
            <tr>
                <th scope="row">{{$loop->index+1}}</th>
                <td>{{\Carbon\Carbon::parse($payment->date_open)->format('d.m.Y')}}</td>
                <td class="text-right">{{number_format($payment->amount, 2, '.', ',')}} (в т.ч. НДС {{number_format($payment->VAT, 2, '.', ',')}})</td>
                <td class="text-left">{{$payment->account_name}}</td>
                <td class="text-left">{{$payment->description}}</td>

               
                <td>
                    {{-- @if(Gate::allows('e-payment'))
                    <a href="{{route('admin.editAgrPayment', ['agreement'=>$agreement, 'payment' => $payment])}}">
                        &#9998;Изменить </a>
                    @endif     --}}
                </td>
                <td>
                    {{-- @if(Gate::allows('e-real_payment'))
                    <a href="{{route('admin.movePaymentToReal', ['agreement'=>$agreement, 'payment' => $payment])}}">
                        &euro; В оплату </a>
                    @endif     --}}
                </td>

                <td>
                    {{-- @if(Gate::allows('e-payment'))
                    <a href="{{route('admin.deleteAgrPayment', ['agreement'=>$agreement, 'payment' => $payment])}}"
                       onclick="return confirm('Действительно удалить данные о платеже?')">
                        &#10008;Удалить </a>
                    @endif     --}}
                </td>
            </tr>
        @empty
            <tr>
                <th colspan="6">Нет записей</th>
            </tr>
        @endforelse
        <tr>
            <th colspan="2">Итого</th>
            <th class="text-right">{{number_format($payments->sum('amount'), 2)}}</th>
            <th class="text-left"></th>
            <th></th>
            <th></th>
        </tr>
        </tbody>
    </table>

    {{-- @if(Gate::allows('e-payment')&&($payments->count()>0))
        <form action="{{route('admin.massDeletePayments', ['agreement'=>$agreement])}}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-danger"
                    onclick="return confirm('Действительно удалить все плановые платежи договора?')"
            >Удалить все платежи
            </button>
        </form>
    @endif --}}

</div>


<div class="row">
    <div class="col-md-12">
        <a class="btn btn-outline-secondary"
           href="{{route('admin.addBankAccount', ['account_owner_id' => $company->id])}}">
            Добавить счет
        </a>
    </div>
</div>

<div class="row m-1">
    <div class="col-md-12">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">№</th>
                <th scope="col">Номер счета</th>
                <th scope="col">Банк</th>
                <th scope="col">Дата открытия</th>
                <th scope="col">Дата закрытия</th>
                <th scope="col"></th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            @forelse($company->bankAccounts as $bankAccount)
                <tr
                    @if ($bankAccount->date_close&&$bankAccount->date_close<now())
                        class="text-secondary text-decoration-line-through"
                    @endif
                >
                    <th scope="row">{{$loop->index+1}}</th>
                    <td>{{$bankAccount->account_number}}</td>
                    <td>{{$bankAccount->bank->name}}</td>
                    <td>{{\Carbon\Carbon::parse($bankAccount->date_open)->format('d.m.Y')}}</td>
                    <td>{{\Carbon\Carbon::parse($bankAccount->date_close)->format('d.m.Y')}}</td>
                    <td>
                        <a href="{{route('admin.editBankAccount', ['bankAccount' => $bankAccount])}}">
                            &#9776;Изменить
                        </a>
                    </td>
                    <td>
                        <a href="{{route('admin.deleteBankAccount', ['bankAccount' => $bankAccount])}}"
                           onclick="return confirm('Действительно удалить банковский счет?')">
                            &#10008;Удалить
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <th colspan="4" class="text-secondary font-italic">Нет данных для отображения</th>
                </tr>
            @endforelse
            </tbody>
        </table>

    </div>
</div>

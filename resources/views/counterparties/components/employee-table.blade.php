<div class="row">
    <div class="col-md-12">
        @if(Gate::allows('e-counterparty'))
            <a class="btn btn-outline-info"
            href="{{route('addCounterpartyEmployee', ['company' => $counterparty])}}">
                Добавить сотрудника
            </a>
        @endif
    </div>
</div>

<div class="row m-1">
    <div class="col-md-12">


        <table class="table">
            <thead>
            <tr>
                <th scope="col">№</th>
                <th scope="col">ФИО</th>
                <th scope="col">Должность</th>
                <th scope="col">Телефон</th>
                <th scope="col">E-mail</th>
                <th scope="col">Дополнительно</th>
                <th scope="col"></th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            @forelse($counterparty->staff as $employee)
                <tr>
                    <th scope="row">{{$loop->index+1}}</th>
                    <td>{{$employee->name}}</td>
                    <td>{{$employee->title}}</td>
                    <td>{{$employee->phone}}</td>
                    <td>{{$employee->email}}</td>
                    <td>{{$employee->description}}</td>
                    <td>
                        @if(Gate::allows('e-counterparty'))
                            <a href="{{route('editCounterpartyEmployee', ['employee' => $employee])}}">
                                &#9776;Изменить
                            </a>
                        @endif
                    </td>
                    <td>
                        @if(Gate::allows('e-counterparty'))
                            <a href="{{route('deleteCounterpartyEmployee', ['employee' => $employee])}}"
                            onclick="return confirm('Действительно удалить запись о сотруднике контргагента?')">
                                &#10008;Удалить
                            </a>
                        @endif
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
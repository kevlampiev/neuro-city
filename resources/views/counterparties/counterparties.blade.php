@extends('layouts.big-form')

@section('title')
    Администратор| Справочники
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">
            <h2>Реестр контрагентов</h2>
        </div>
    </div>

    @if ($filter!=='')
        <div class="alert alert-info pt-0 pb-0" role="alert">
            Установлен фильтр по имени " <strong> {{$filter}} </strong> "
        </div>
    @endif

    <div class="row">
        <div class="col-md-5">
            @if(Gate::allows('e-counterparty'))
                <a class="btn btn-outline-info" href="{{route('addCounterparty')}}">Новый Контрагент</a>
            @endif
        </div>
        <div class="col-md-4"> </div>
        <div class="col-md-3">
            <form class="d-flex" method="GET">
                <input class="form-control mr-sm-2" type="search" placeholder="Поиск контрагента" aria-label="Search"
                       name="searchStr"
                       value="{{isset($filter)?$filter:''}}" >
                <button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Поиск</button>
            </form>
        </div>

    </div>

    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col"></th>
                    <th scope="col">Наименование</th>
                    <th scope="col">Тип</th>
                    <th scope="col">ИНН</th>
                    <th scope="col">Телефон</th>
                    <th scope="col">ЭДО</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                @forelse($counterparties as $index=>$counterparty)
                    <tr>
                        <th scope="row">{{$index + 1}}</th>
                        <td scope="row">
                            @if($counterparty->adesk_id)
                            <i class="bi bi-arrow-left-right"></i> ADesk
                            @else
                            --
                            @endif
                        </td>
                        <td>{{$counterparty->name}}</td>
                        <td>
                            @switch($counterparty->company_type)
                                @case('bank')
                                <span> Банк</span>
                                @break
                                @case('insurer')
                                <span>Страховая компания</span>
                                @break
                                @case('lessor')
                                <span>Лизинговая компания</span>
                                @break
                                @case('goverment')
                                <span>Государственное учреждение</span>
                                @break
                                @case('other')
                                <span>--</span>
                                @break
                                @default
                                <span>??</span>
                            @endswitch
                        </td>
                        <td>{{$counterparty->inn}}</td>
                        <td>{{$counterparty->phone}}</td>
                        <td> @if($counterparty->uses_edo)<i class="bi bi-lightning-charge-fill"></i>@endif </td>
                        <td>
                            <a href="{{route('counterpartySummary',['counterparty'=>$counterparty])}}">
                                &#9776;Карточка </a>
                        </td>
                        <td>
                            @if(Gate::allows('e-counterparty'))
                                <a href="{{route('editCounterparty',['counterparty'=>$counterparty])}}"> &#9998;Изменить </a>
                            @endif
                        </td>
                        @if ($counterparty->agreements_sail->isEmpty()&&$counterparty->agreements_buy->isEmpty()&&Gate::allows('e-counterparty'))                            
                            <td><a href="{{route('deleteCounterparty',['counterparty'=>$counterparty])}}"
                                   onclick="return confirm('Действительно удалить данные о контрагенте?')">
                                    &#10008;Удалить </a>
                            </td>
                        @else
                            <td>
                                <p class="text-muted">&#10008;Удалить </p>
                            </td>
                        @endif
                    </tr>
                @empty
                    <td colspan="7">Нет записей</td>
                @endforelse
                </tbody>
            </table>
            {{$counterparties->appends(['searchStr'=>$filter ])->links()}}

        </div>
    </div>
@endsection

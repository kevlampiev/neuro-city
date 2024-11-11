@extends('layouts.big-form')

@section('title')
    Платежи
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">
            <h2>Платежи</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-2">
            <a class="btn btn-outline-info" href="{{route('payments.create')}}">Добавить платеж</a>
        </div>

        <div class="col-md-10">
            <form class="form-inline my-2 my-lg-0" method="GET">
                <div class="d-flex align-items-center">
                    <div class="input-group me-2">
                        <span class="input-group-text" id="cfsItem">Временной период с </span>
                        <input class="form-control" type="date" aria-label="Search"
                            name="filterDateStart"
                            value="{{$filterDateStart??''}}">
                        <span class="input-group-text" id="cfsItem"> по </span>
                        <input class="form-control" type="date" aria-label="Search"
                            name="filterDateEnd"
                            value="{{$filterDateEnd??''}}">    
                        <span class="input-group-text" id="cfsItem">Текст для поиска</span>
                        <input class="form-control" type="search" placeholder="Поиск в договорах" aria-label="Search"
                            name="searchStr"
                            value="{{isset($filter)?$filter:''}}">
                    </div>
                    <button class="btn btn-outline-primary" type="submit">Поиск</button>
                </div>
            </form>
        </div>
    </div>



    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Дата</th>
                    <th scope="col">Банковский счет</th>
                    <th scope="col">Договор</th>
                    <th scope="col">Проект</th>
                    <th scope="col">Статья</th>
                    <th scope="col">Сумма, руб</th>
                    <th scope="col">НДС, руб</th>
                    <th scope="col">Комментарий</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                @forelse($payments as $index => $item)
                    <tr>
                        <th scope="row">{{$index+1}}</th>
                        <td> {{\Carbon\Carbon::parse($item->date_open)->format('d.m.Y')}}</td>
                        <td>
                            Р/cч {{$item->account_name}}
                        </td>
                        <td>
                            <p class="m-0">{{$item->agreement_name}} </p>
                            <p class="fst-italic text-secondary m-0">{{$item->seller_name}} </p>
                            <p class="fst-italic text-secondary m-0">{{$item->buyer_name}} </p>
                        </td>
                        <td>
                                {{$item->project_name}}
                        </td>
                        <td>{{$item->cfs_item_name}}</td>
                        <td class="text-end">{{number_format($item->amount, 2, '.', ',')}}</td>
                        <td class="text-end">{{number_format($item->VAT, 2, '.', ',')}}</td>
                        <td>{{$item->description}}</td>
                        <td>
                            @if(Gate::allows('e-payments'))
                                <a href="{{route('payments.edit', ['payment' => $item->id])}}"> <i class="bi bi-pencil-square"></i> Изменить </a>
                            @endif    
                        </td>
                        
                        <td>
                            @if(Gate::allows('e-payments'))
                                <form action="{{ route('payments.destroy', ['payment' => $item->id]) }}" method="POST" onsubmit="return confirm('Действительно удалить данные о платеже?')">
                                    @csrf
                                    @method('DELETE') <!-- Указываем метод DELETE -->
                                    <button type="submit" class="btn btn-outline-danger" style="border: none;">&#10008;Удалить</button>
                                </form>
                            @endif
                        </td>

                    </tr>
                @empty
                    <td colspan="10">Нет записей</td>
                @endforelse
                </tbody>
            </table>
            {!! $payments->appends(request()->input())->links() !!}
        </div>
    </div>
@endsection

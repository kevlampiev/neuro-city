@extends('layouts.big-form')

@section('title')
    Банковские счета
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">
            <h2>Банковские счета</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <a class="btn btn-outline-info" href="{{route('accounts.create')}}">Добавить счет</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Владелец счета</th>
                    <th scope="col">Банк</th>
                    <th scope="col">Номер счета</th>
                    <th scope="col">Дата открытия</th>
                    <th scope="col">Дата закрытия</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                @forelse($accounts as $index => $item)
                    <tr>
                        <th scope="row">{{$index+1}}</th>
                        <td> {{$item->owner->name}}</td>
                        <td>{{$item->bank->name}}</td>
                        <td>{{$item->account_number}}</td>
                        <td>{{\Carbon\Carbon::parse($item->date_open)->format('d.m.Y')}}</td>
                        <td>{{$item->date_close?\Carbon\Carbon::parse($item->date_close)->format('d.m.Y'):'--'}}</td>
                        <td>
                            <a href="{{route('accounts.summary', ['bankAccount' => $item])}}"> <i class="bi bi-card-heading"></i> Карточка </a>
                        </td>
                        <td>
                            @if(Gate::allows('e-accounts'))
                                <a href="{{route('accounts.edit', ['bankAccount' => $item->id])}}"> <i class="bi bi-pencil-square"></i> Изменить </a>
                            @endif    
                        </td>
                        
                        <td>
                            @if(Gate::allows('e-accounts'))
                                <form action="{{ route('accounts.destroy', ['bankAccount' => $item->id]) }}" method="POST" onsubmit="return confirm('Действительно удалить данные о типе дроидов?')">
                                    @csrf
                                    @method('DELETE') <!-- Указываем метод DELETE -->
                                    <button type="submit" class="btn btn-outline-danger" style="border: none;">&#10008;Удалить</button>
                                </form>
                            @endif
                        </td>

                    </tr>
                @empty
                    <td colspan="9">Нет записей</td>
                @endforelse
                </tbody>
            </table>
             <!-- Пагинация -->
            <div class="d-flex justify-content-center">
                {{ $accounts->links() }}
            </div>
        </div>
    </div>
@endsection

@extends('layouts.big-form')

@section('title')
    Типы Дроидов
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">
            <h2>Реализуемые продукты и услуги</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <a class="btn btn-outline-info" href="{{route('droidTypes.create')}}">Добавить тип</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Название</th>
                    <th scope="col">Продукт или услуга?</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                @forelse($items as $index => $item)
                    <tr>
                        <th scope="row">{{$index+1}}</th>
                        <td> {{$item->name}}</td>
                        <td>{{$item->is_service?"Услуга":"Продукт"}}</td>
                        @if(Auth::user()->is_superuser)
                            <td>
                                <a href="{{route('products.edit', ['id' => $item->id])}}"> <i class="bi bi-pencil-square"></i> Изменить </a>
                            </td>
                            <td>
                                <form action="{{ route('products.destroy', ['id' => $item->id]) }}" method="POST" onsubmit="return confirm('Действительно удалить данные о продукте/услуге?')">
                                    @csrf
                                    @method('DELETE') <!-- Указываем метод DELETE -->
                                    <button type="submit" class="btn btn-outline-danger" style="border: none;">&#10008;Удалить</button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @empty
                    <td colspan="5">Нет записей</td>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

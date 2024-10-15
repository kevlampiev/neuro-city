@extends('layouts.big-form')

@section('title')
    Типы Дроидов
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">
            <h2>Типы Дроидов</h2>
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
                    <th scope="col">Описание</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                @forelse($items as $index => $item)
                    <tr>
                        <th scope="row">{{$index+1}}</th>
                        <td> {{$item->name}}</td>
                        <td>{{$item->description}}</td>
                        <td>
                            <a href="{{route('droidTypes.summary', ['droidType' => $item])}}"> <i class="bi bi-card-heading"></i> Карточка </a>
                        </td>
                        <td>
                            @if(Gate::allows('e-droid_types'))
                                <a href="{{route('droidTypes.edit', ['id' => $item->id])}}"> <i class="bi bi-pencil-square"></i> Изменить </a>
                            @endif    
                        </td>
                        
                        <td>
                            @if(Gate::allows('e-droid_types'))
                                <form action="{{ route('droidTypes.destroy', ['id' => $item->id]) }}" method="POST" onsubmit="return confirm('Действительно удалить данные о типе дроидов?')">
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
        </div>
    </div>
@endsection

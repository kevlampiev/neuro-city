@extends('layouts.big-form')

@section('title')
    Проекты
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">
            <h2>Проекты</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <a class="btn btn-outline-info" href="{{route('projects.create')}}">Добавить проект</a>
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
                    <th scope="col">Дата старта</th>
                    <th scope="col">Дата окончания</th>
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
                        <td>{{$item->date_open}}</td>
                        <td>{{$item->date_close}}</td>
                        <td>{{$item->role}}</td>
                        <td>
                            <a href="{{route('projects.summary', ['project' => $item])}}"> <i class="bi bi-card-heading"></i> Карточка </a>
                        </td>
                        <td>
                            @if(Gate::allows('e-projects'))
                                <a href="{{route('projects.edit', ['project' => $item])}}"> <i class="bi bi-pencil-square"></i> Изменить </a>
                            @endif    
                        </td>
                        
                        <td>
                            @if(Gate::allows('e-projects'))
                                <form action="{{ route('projects.destroy', ['project' => $item->id]) }}" method="POST" onsubmit="return confirm('Действительно удалить данные о проекте?')">
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

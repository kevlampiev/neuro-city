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
                            {{-- <a href="{{route('userSummary', ['user' => $item])}}"> <i class="bi bi-card-heading"></i> Карточка </a> --}}
                        </td>
                        <td>
                            <a href="{{route('projects.edit', ['project' => $item])}}"> <i class="bi bi-pencil-square"></i> Изменить </a>
                        </td>
                        <td> 
                            {{-- @if (Auth::user()->id!==$item->id)
                                <a href="{{route('setTempPassword',['user' => $item])}}"> <i class="bi bi-incognito"></i> Сменить
                                    пароль </a>
                            @else
                                <a href="{{route('password.expired')}}"> <i class="bi bi-incognito"></i> Сменить пароль </a>
                                                                {{-- <a href="{{route('password.expired')}}"> &#9998;Сменить пароль </a> 
                            @endif --}}
                        </td>
                        <td>
                            {{-- @if (Auth::user()->id!==$item->id)
                                <a href="{{route('deleteUser', ['user' => $item])}}"
                                   onclick="return confirm('Действительно удалить данные о пользователе?')"> &#10008;Удалить </a>
                            @endif --}}
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

@extends('layouts.big-form')

@section('title')
    Администратор| Пользователи
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">
            <h2>Пользователи системы</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <a class="btn btn-outline-info" href="{{route('addUser')}}">Добавить пользователя</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col"></th>
                    <th scope="col">Имя</th>
                    <th scope="col">e-mail</th>
                    <th scope="col">Телефон</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                @forelse($users as $index => $user)
                    <tr @if ($user->is_superuser) class="table-warning"  @endif>
                        <th scope="row">{{$index+1}}</th>
                        <td> @if ($user->is_superuser) <i class="bi bi-battery-charging"></i>  @endif</td>
                        <td>{{$user->name}}</td>
                        <td>{{$user->email}}</td>
                        <td>{{$user->phone_number}}</td>
                        <td>{{$user->role}}</td>
                        <td>
                            <a href="{{route('userSummary', ['user' => $user])}}"> <i class="bi bi-card-heading"></i> Карточка </a>
                        </td>
                        <td>
                            <a href="{{route('editUser', ['user' => $user])}}"> <i class="bi bi-pencil-square"></i> Изменить </a>
                        </td>
                        <td> @if (Auth::user()->id!==$user->id)
                                <a href="{{route('setTempPassword',['user' => $user])}}"> <i class="bi bi-incognito"></i> Сменить
                                    пароль </a>
                            @else
                                <a href="#"> <i class="bi bi-incognito"></i> Сменить пароль </a>
                                                                {{-- <a href="{{route('password.expired')}}"> &#9998;Сменить пароль </a> --}}
                            @endif
                        </td>
                        <td>
                            @if (Auth::user()->id!==$user->id)
                                <a href="{{route('deleteUser', ['user' => $user])}}"
                                   onclick="return confirm('Действительно удалить данные о пользователе?')"> &#10008;Удалить </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <td colspan="6">Нет записей</td>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

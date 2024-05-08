@extends('layouts.admin')

@section('title')
    Администратор| Роли пользователей
@endsection

@section('content')

    <div class="row">
        <h2>Роли в системе</h2>
        <div>
            <a class="btn btn-outline-info" href="{{route('admin.addRole', [])}}">Добавить роль</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Описание</th>
                    <th scope="col">Код</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                @forelse($roles as $role)
                <tr>
                    <td>{{$loop->index +1}}</td>
                    <td>{{$role->name}}</td>
                    <td>{{$role->slug}}</td>
                    <td><a href="{{route('admin.roleSummary',['role' => $role])}}"> &#9776;Карточка </a></td>
                    <td><a href="{{route('admin.editRole',['role' => $role])}}"> &#9998;Изменить </a></td>
                    <td><a href="{{route('admin.deleteRole',['role' => $role])}}"
                           onclick="return confirm('Действительно удалить роль?')"> &#10008;Удалить </a>
                    </td>
                </tr>
                @empty
                    <tr>
                        <td colspan="5">Нет данных</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

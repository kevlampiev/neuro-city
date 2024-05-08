@extends('layouts.admin')

@section('title')
    Администратор|Добавить роль пользователю
@endsection

@section('content')
    <h3> Добавить роль пользователю  {{$user->name}}</h3>
    <form method="POST">
        @csrf
        <div class="input-group mb-3">
            <label for="roles">Доступные роли</label>
            <select name="role_id"
                    class="form-control selectpicker" id="roles" data-live-search="true">
                @foreach ($roles as $role)
                    <option
                        value="{{$role->id}}">
                        {{$role->name}} /{{$role->slug}}
                    </option>
                @endforeach
            </select>
        </div>


        <button type="submit" class="btn btn-primary">
            Добавить
        </button>
        <a class="btn btn-secondary" href="{{route('admin.userSummary',['user'=>$user, 'page' => 'permissions'])}}">Отмена</a>
    </form>

@endsection

@section('scripts')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#roles').select2();
        })
    </script>
@endsection



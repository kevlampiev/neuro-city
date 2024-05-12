@extends('layouts.big-form')

@section('title')
    Администратор|Добавить роль пользователю
@endsection

@section('content')
    <h3> Добавить разрещение пользователю  {{$user->name}}</h3>
    <form method="POST">
        @csrf
        <div class="mb-3">
            <label for="permissions">Доступные разрешения</label>
            <select name="permission_id"
                    class="form-control selectpicker" id="permissions" data-live-search="true">
                @foreach ($permissions as $permission)
                    <option
                        value="{{$permission->id}}">
                        {{$permission->name}} /{{$permission->slug}}
                    </option>
                @endforeach
            </select>
        </div>


        <button type="submit" class="btn btn-primary">
            Добавить
        </button>
        <a class="btn btn-secondary" href="{{route('userSummary',['user'=>$user, 'page' => 'permissions'])}}">Отмена</a>
    </form>

@endsection

@section('scripts')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#permissions').select2();
        })
    </script>
@endsection



@extends('layouts.big-form')

@section('title')
    Добавить подписчика к задаче
@endsection

@section('content')
    <h3> Добавить подписчика к задаче {{$task->subject}}</h3>
    <form method="POST">
        @csrf
        <div class="input-group mb-3">
            <label for="users"></label>
            <select name="user_id"
                    class="form-control selectpicker" id="users" data-live-search="true">
                @foreach ($users as $user)
                    <option
                        value="{{$user->id}}">
                        {{$user->name}} /{{$user->email}}
                    </option>
                @endforeach
            </select>
        </div>


        <button type="submit" class="btn btn-primary">
            Добавить
        </button>
        <a class="btn btn-secondary" href="{{route('taskCard',['task'=>$task])}}">Отмена</a>
    </form>

@endsection

@section('scripts')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#users').select2();
        })
    </script>
@endsection


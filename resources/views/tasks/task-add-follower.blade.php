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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script>
        
        document.addEventListener('DOMContentLoaded', () => {
        new Choices('#users', {
            searchEnabled: true,
            placeholderValue: 'Выберите подписчика по имени',
            shouldSort: false,
        });
        
    });
    </script>
@endsection



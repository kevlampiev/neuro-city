@extends('layouts.big-form')

@section('title')
    Задачи пользователя
@endsection

@section('content')

    <div class="row mb-2">
        <h2>Список моих задач</h2>
        <div class="col-md-7">
            
            <a class="btn btn-outline-info" href="{{route('addTask')}}">Создать задачу</a>
        </div>
        <div class="col-md-5">
            <form class="form-inline my-2 my-lg-0 d-flex align-items-center" method="GET">
                <input class="form-control mr-sm-2" type="search" placeholder="Поиск в моих задачах" aria-label="Search"
                       name="searchStr"
                       value="{{isset($filter)?$filter:''}}">
                <button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Поиск</button>
            </form>
        </div>

    </div>

    <div class="row">
        <div class="col-md-6">
            <h3> Задачи, назначенные мне</h3>
            <ul id="userAssignments">
                @foreach($userTasks as $task)
                    <li class="task-item"> @include('tasks.task-record') </li>
                @endforeach
            </ul>
        </div>
        <div class="col-md-6">
            <h3> Задачи, где я постановщик или наблюдатель</h3>
            <ul id="otherTasks">
                @foreach($interessantTasks as $task)
                    <li class="task-item"> @include('tasks.task-record') </li>
                @endforeach
            </ul>
        </div>
    </div>

@endsection


@section('styles')
    <!-- Подключение CSS -->
    <link rel="stylesheet" href="{{ asset('css/DraggableNestableList.min.css') }}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <style>
        .task-item {
            list-style-type: none;
            outline: 1px solid lightgrey;
            margin: 3px;
            padding: 5px;
        }
        .task-description {
            text-decoration: none;
        }
        .task-description:hover {
            background:lightgrey;
        }
    </style>
    
@endsection

@section('scripts')
<!-- Подключение JavaScript -->
<script src="{{ asset('js/DraggableNestableList.js') }}"></script>
<script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <script defer>
        let dnl1 =new DraggableNestableList("#userAssignments");
        let dnl2 =new DraggableNestableList("#otherTasks");
    </script>
@endsection
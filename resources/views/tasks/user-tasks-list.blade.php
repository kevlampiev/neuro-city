@extends('layouts.big-form')

@section('title')
    Задачи пользователя
@endsection

@section('content')

    <div class="row mb-2">
        <h2>Список моих задач</h2>
        <div class="col-md-7">           
            <a class="btn btn-outline-info" href="{{route('addTask')}}"> <i class="bi bi-file-earmark-plus"></i>Создать задачу</a>
            <a class="btn btn-outline-info" href="{{route('userTasks', ['user' => auth()->user()])}}"><i class="bi bi-diagram-3"></i> Дерево задач</a>
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
        <div class="col-md-4">
            <h3> Назначено мне</h3>
            <ul id="userAssignments">
                @foreach($userAssignments as $task)
                    <li class="task-item"> @include('tasks.task-record') </li>
                @endforeach
            </ul>
        </div>
        <div class="col-md-4">
            <h3> Делегировано мной </h3>
            <ul id="assignedByUser">
                @foreach($assignedByUser as $task)
                    <li class="task-item"> @include('tasks.task-record') </li>
                @endforeach
            </ul>
        </div>
        <div class="col-md-4">
            <h3> Наблюдаю</h3>
            <ul id="otherTasks">
                @foreach($followerTasks as $task)
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
        let dnl2 =new DraggableNestableList("#assignedByUser");
        let dnl3 =new DraggableNestableList("#otherTasks");
    </script>
@endsection
@extends('layouts.big-form')

@section('title')
    Задачи пользователя
@endsection

@section('content')

    <div class="row mb-2">
        <h2>Мои задачи</h2>
        <div class="col-md-7">
            <a class="btn btn-outline-info" href="{{route('addTask')}}"> <i class="bi bi-file-earmark-plus"></i>Создать задачу</a>
            <a class="btn btn-outline-info" href="{{route('userTaskList', ['user' => auth()->user()])}}"><i class="bi bi-list-task"></i> Список задач</a>
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
        <div class="col-md-12">
            <div class="accordion" id="userTasksAccordeon">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-HeadingAssignedToUser">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#panelsStayOpen-collapseAssignedToUser" aria-expanded="true"
                                aria-controls="panelsStayOpen-collapseAssignedToUser">
                            Задачи, назначенные мне &nbsp;&nbsp;
                            <span class="badge text-white bg-info">{{count($userAssignments)}}</span>
                        </button>
                    </h2>
                    <div id="panelsStayOpen-collapseAssignedToUser" class="accordion-collapse collapse show"
                         aria-labelledby="panelsStayOpen-HeadingAssignedToUser">
                        <div class="accordion-body">
                            @include('tasks.tasks-tree', ['tasks'=>$userAssignments, 'listId'=>'userAssignments'])
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false"
                                aria-controls="panelsStayOpen-collapseTwo">
                            Задачи, делегированные мной другим сотрудникам&nbsp;&nbsp;
                            <span class="badge text-white bg-info">{{count($assignedByUser)}}</span>
                        </button>
                    </h2>
                    <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse"
                         aria-labelledby="panelsStayOpen-headingTwo">
                        <div class="accordion-body">
                            @include('tasks.tasks-tree', ['tasks'=>$assignedByUser, 'listId' => 'assignedByUser'])
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false"
                                aria-controls="panelsStayOpen-collapseThree">
                            Задачи, в которых я являюсь подписчиком&nbsp;&nbsp;
                            <span class="badge text-white bg-info">{{count($followerTasks)}}</span>
                        </button>
                    </h2>
                    <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse"
                         aria-labelledby="panelsStayOpen-headingThree">
                        <div class="accordion-body">
                            <div class="accordion-body">
                                @include('tasks.tasks-tree', ['tasks'=>$followerTasks, 'listId' => 'followerTasks'])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
        let dnl3 =new DraggableNestableList("#followerTasks")
    </script>

@endsection


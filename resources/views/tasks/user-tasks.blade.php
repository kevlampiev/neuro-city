@extends('layouts.big-form')

@section('title')
    Задачи пользователя
@endsection

@section('content')

    <div class="row">
        <h2>Мои задачи</h2>
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
                            @include('tasks.components.tasks-tree', ['tasks'=>$userAssignments])
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
                            @include('tasks.components.tasks-tree', ['tasks'=>$assignedByUser])
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
                                @include('tasks.components.tasks-tree', ['tasks'=>$followerTasks])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection


@section('script')

@endsection

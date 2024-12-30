@extends('layouts.big-form')

@section('title')
    Карточка задачи
@endsection

@section('content')

    <div class="row">

        <div class="col-md-12">
            <h2>Карточка задачи </h2>
        </div>
    </div>

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active"
                    id="main-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#main"
                    type="button"
                    role="tab"
                    aria-controls="main"
                    aria-selected="true">
                <i class="fa fa-university" aria-hidden="true"></i>
                Основная информация
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link"
                    id="followers-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#followers"
                    type="button"
                    role="tab"
                    aria-controls="followers"
                    aria-selected="false">
                <i class="fa fa-users" aria-hidden="true"></i>
                Подписчики
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link"
                    id="subtasks-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#subtasks"
                    type="button"
                    role="tab"
                    aria-controls="subtasks"
                    aria-selected="false">
                <i class="fa fa-tasks" aria-hidden="true"></i>
                Дочерние задачи
            </button>
        </li>
        @if(Gate::allows('s-agreements'))
            <li class="nav-item" role="presentation">
                <button class="nav-link"
                        id="agreements-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#agreements"
                        type="button"
                        role="tab"
                        aria-controls="agreements"
                        aria-selected="false">
                    <i class="fa fa-files-o" aria-hidden="true"></i>
                    Договоры
                </button>
            </li>
        @endif    

        @if(Gate::allows('s-counterparty'))
            <li class="nav-item" role="presentation">
                <button class="nav-link"
                        id="companies-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#companies"
                        type="button"
                        role="tab"
                        aria-controls="companies"
                        aria-selected="false">
                    <i class="fa fa-files-o" aria-hidden="true"></i>
                    Компании
                </button>
            </li>
        @endif    

        <li class="nav-item" role="presentation">
            <button class="nav-link"
                    id="messages-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#messages"
                    type="button"
                    role="tab"
                    aria-controls="messages"
                    aria-selected="false">
                <i class="fa fa-comments-o" aria-hidden="true"></i>
                Сообщения
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link"
                    id="documents-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#documents"
                    type="button"
                    role="tab"
                    aria-controls="documents"
                    aria-selected="false">
                <i class="fa fa-comments-o" aria-hidden="true"></i>
                Документы по задаче
            </button>
        </li>
    </ul>

    {{--    Ниже идет содержимое вкладок--}}
    <div class="row">
        <div class="col-md-12">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="main" role="tabpanel" aria-labelledby="main-tab">
                    <h4>Основная информация</h4>
                    @include('tasks.components.commom-info-menu')
                    @include('tasks.components.common-info-task-data')
                </div>
                <div class="tab-pane fade" id="followers" role="tabpanel" aria-labelledby="followers-tab">
                    <h4>Подписчики</h4>
                    @include('tasks.components.followers-menu')
                    @include('tasks.components.followers-data')
                </div>
                <div class="tab-pane fade" id="subtasks" role="tabpanel" aria-labelledby="subtasks-tab">
                    <h4>Дочерние задачи</h4>
                    @include('tasks.components.subtasks-menu')
                    @include('tasks.tasks-tree', ['tasks'=>$task->subTasks, 'listId'=>'subtasks'])
                </div>
                 @if(Gate::allows('s-agreements'))
                    <div class="tab-pane fade" id="agreements" role="tabpanel" aria-labelledby="agreements-tab">
                        <h4>Связанные договоры</h4>
                        @include('tasks.components.agreements-menu')
                        @include('tasks.components.agreements-data')
                    </div>
                @endif  

                @if(Gate::allows('s-counterparty'))
                    <div class="tab-pane fade" id="companies" role="tabpanel" aria-labelledby="companies-tab">
                        <h4>Связанные контрагенты</h4>
                        @include('tasks.components.companies-menu')
                        @include('tasks.components.companies-data')
                    </div>
                @endif  

                <div class="tab-pane fade" id="messages" role="tabpanel" aria-labelledby="messages-tab">
                    <h4>Сообщения</h4>
                    @include('tasks.components.messages-menu')
                    <div class="card bg-light">
                        @include('tasks.messages.messages', ['messages' => $task->messages])
                    </div>
                </div>

                <div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="documents-tab">
                    <h4>Документы по задаче</h4>
                    @include('tasks.components.documents-menu')
                    <div class="card bg-light">
                        @include('tasks.components.documents-data', ['documents' => $task->documents])
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
        }
        .task-description {
            text-decoration: none;
        }
        .task-description:hover {
            background:lightgrey;
        }
    
        summary::-webkit-details-marker {
            display: none
        }

        summary:before {
            /*background: url(some-picture);*/
            float: left;
            /*height: 20px;*/
            width: 10px;
            content: "+";
        }

        details[open] > summary:before {
            /*background: url(other-picture);*/
            content: "-";
        }

    </style>

@endsection

@section('scripts')

<!-- Подключение JavaScript -->
    <script src="{{ asset('js/DraggableNestableList.js') }}"></script>
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <script defer>
        let dnl =new DraggableNestableList("#subtasks");

        function autoSelectPage() {
            let urlArr = document.location.pathname.split('/')
            if (urlArr.length === 5) {
                let tabName = '[data-bs-target="#' + urlArr[4] + '"]'
                $(tabName).tab('show')
            }
        }

        document.addEventListener("DOMContentLoaded", autoSelectPage);
    </script>

@endsection




@section('scripts')

@endsection


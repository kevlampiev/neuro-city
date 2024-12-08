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
        <li class="nav-item" role="presentation">
            <button class="nav-link"
                    id="documents-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#documents"
                    type="button"
                    role="tab"
                    aria-controls="documents"
                    aria-selected="false">
                <i class="fa fa-files-o" aria-hidden="true"></i>
                Документы
            </button>
        </li>
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

    </ul>

    {{--    Ниже идет содержитмое вкладок--}}
    <div class="row">
        <div class="col-md-12">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="main" role="tabpanel" aria-labelledby="main-tab">
                    <h4>Основная информация</h4>
                    @if($task->parent_task_id)
                        @include('tasks.components.commom-info-menu')
                        @include('tasks.components.common-info-task-data')
                    @else
                        @include('tasks.components.commom-info-project-menu')
                        @include('tasks.components.common-info-project-data')
                    @endif
                </div>
                <div class="tab-pane fade" id="followers" role="tabpanel" aria-labelledby="followers-tab">
                    <h4>Подписчики</h4>
                    @include('tasks.components.followers-menu')
                    @include('tasks.components.followers-data')
                </div>
                <div class="tab-pane fade" id="subtasks" role="tabpanel" aria-labelledby="subtasks-tab">
                    <h4>Дочерние задачи</h4>
                    @include('tasks.components.subtasks-menu')
                    @include('tasks.components.subtasks-data')
                </div>
                <div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="documents-tab">
                    <h4>Документы по задаче</h4>
                    @include('tasks.components.documents-menu')
                    @include('tasks.components.documents-data')
                </div>
                <div class="tab-pane fade" id="messages" role="tabpanel" aria-labelledby="messages-tab">
                    <h4>Сообщения</h4>
                    @include('tasks.components.messages-menu')
                    <div class="card bg-light">
                        {{-- @include('messages.messages', ['messages' => $task->messages]) --}}
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection


@section('styles')
    <style>
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
    <script>
        function autoSelectPage() {
            let urlArr = document.location.pathname.split('/')
            if (urlArr.length === 6) {
                let tabName = '[data-bs-target="#' + urlArr[5] + '"]'
                $(tabName).tab('show')
            }
        }

        document.addEventListener("DOMContentLoaded", autoSelectPage);
    </script>

@endsection

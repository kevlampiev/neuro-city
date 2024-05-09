@extends('layouts.big-form')

@section('title')
    Администратор|Карточка пользователя
@endsection


@section('content')
    <h3>Карточка Пользователя {{$user->name}}</h3>


    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
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

            {{-- <button class="nav-link"
                    id="tasks-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#tasks"
                    type="button"
                    role="tab"
                    aria-controls="tasks"
                    aria-selected="true">
                <i class="fa fa-tasks" aria-hidden="true"></i>
                Задачи пользователя
            </button> --}}

            <button class="nav-link"
                    id="permissions-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#permissions"
                    type="button"
                    role="tab"
                    aria-controls="permissions"
                    aria-selected="true">
                <i class="fa fa-tasks" aria-hidden="true"></i>
                Разрешения пользователя
            </button>
        </div>
    </nav>


    <div class="tab-content p-2" id="nav-tabContent">
        <div class="tab-pane fade show active" id="main" role="tabpanel" aria-labelledby="main-tab">
            <h4>Основные данные</h4>
            @include('user.components.user-main')
        </div>

        {{-- <div class="tab-pane fade" id="tasks" role="tabpanel" aria-labelledby="tasks-tab">
            <h4>Задачи</h4>
            <div class="row m-1">
                <div class="col-md-12">
                    @include('user.components.user-tasks')
                </div>
            </div>
        </div> --}}

        <div class="tab-pane fade" id="permissions" role="tabpanel" aria-labelledby="permissions-tab">
            <h4>Разрешения пользователя</h4>
            <div class="row m-1">
                <div class="col-md-12">
                    @include('user.components.user-privilegies')
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        function autoSelectPage() {
            let urlArr = document.location.pathname.split('/')
            if (urlArr.length === 6) {
                let tabName = '[data-bs-target="#' + urlArr[5] + '"'
                $(tabName).tab('show')
            }
        }

        function confirmDetaching(event, label)
        {
            event.stopPropagation();
            return confirm(label);
        }


        document.addEventListener("DOMContentLoaded", autoSelectPage);
    </script>

@endsection

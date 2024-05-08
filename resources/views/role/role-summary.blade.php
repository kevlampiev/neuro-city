@extends('layouts.big-form')

@section('title')
    Администратор| Карточка роли
@endsection

@section('content')

    <div>
        <h2>Карточка Роли</h2>
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
                    id="permissions-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#permissions"
                    type="button"
                    role="tab"
                    aria-controls="permissions"
                    aria-selected="false">
                <i class="fa fa-flag-checkered" aria-hidden="true"></i>
                Разрешения роли
            </button>
        </li>

        <li class="nav-item" role="users">
            <button class="nav-link"
                    id="users-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#users"
                    type="button"
                    role="tab"
                    aria-controls="users"
                    aria-selected="false">
                <i class="fa fa-users" aria-hidden="true"></i>
                Сотрудники
            </button>
        </li>
    </ul>

    {{--    Ниже идет содержитмое вкладок--}}
    <div class="row">
        <div class="col-md-12">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="main" role="tabpanel" aria-labelledby="main-tab">
                    <h4>Основная информация</h4>
                    @include("role.components.common-info");
                </div>
                <div class="tab-pane fade" id="permissions" role="tabpanel" aria-labelledby="permissions-tab">
                    <h4>Разрешения роли</h4>
                    @include("role.components.permissions-table");
                </div>
                <div class="tab-pane fade" id="users" role="tabpanel" aria-labelledby="users-tab">
                    <h4>Сотрудники, которым назначена данная роль</h4>
                    @include("role.components.users-table");
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

@extends('layouts.big-form')

@section('title')
    Карточка проекта
@endsection

@section('content')
    <h3>Карточка проекта {{$model->name}}</h3>

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

             @if(Gate::allows('s-agreements'))
                <button class="nav-link"
                        id="agreements-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#agreements"
                        type="button"
                        role="tab"
                        aria-controls="agreements"
                        aria-selected="true">
                    <i class="fa fa-file-text" aria-hidden="true"></i>
                    Заключенные договоры
                </button>
            @endif

            <button class="nav-link"
                    id="notes-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#notes"
                    type="button"
                    role="tab"
                    aria-controls="notes"
                    aria-selected="true">
                <i class="fa fa-file-text" aria-hidden="true"></i>
                Заметки по проекту
            </button>
        </div>
    </nav>


    <div class="tab-content p-2" id="nav-tabContent">
        <div class="tab-pane fade show active" id="main" role="tabpanel" aria-labelledby="main-tab">
            <h4>Основные данные</h4>
            @include('projects.components.common-info')
        </div>

        <div class="tab-pane fade" id="agreements" role="tabpanel" aria-labelledby="agreements">
            <h4>Договоры, относящиеся к проекту</h4>
            @include('projects.components.agreements-table')
        </div>

        <div class="tab-pane fade" id="notes" role="tabpanel" aria-labelledby="notes-tab">
            <h4>Заметки по проекту</h4>
            @include('projects.components.notes-table')
        </div>

    </div>

@endsection

@section('scripts')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Подключаем jQuery -->

    <script>
        function autoSelectPage() {
            let urlArr = document.location.pathname.split('/')
            if (urlArr.length === 5) {
                let tabName = '[data-bs-target="#' + urlArr[4] + '"'
                $(tabName).tab('show')
                alert(urlArr[3])
            }
        }

        document.addEventListener("DOMContentLoaded", autoSelectPage);
    </script>

@endsection

@extends('layouts.big-form')

@section('title')
    Администратор| Карточка компании
@endsection


@section('content')
    <h3>Карточка компании {{$company->name}}</h3>


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
                    id="poas-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#poas"
                    type="button"
                    role="tab"
                    aria-controls="poas"
                    aria-selected="true">
                <i class="fa fa-file-text-o" aria-hidden="true"></i>
                Выданные доверенности
            </button> --}}
            <button class="nav-link"
                    id="bankAccounts-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#bankAccounts"
                    type="button"
                    role="tab"
                    aria-controls="bankAccounts"
                    aria-selected="true">
                <i class="fa fa-credit-card" aria-hidden="true"></i>
                Банковские счета
            </button>
        </div>
    </nav>


    <div class="tab-content p-2" id="nav-tabContent">
        <div class="tab-pane fade show active" id="main" role="tabpanel" aria-labelledby="main-tab">
            <h4>Основные данные</h4>
            @include('companies.components.common-info')
        </div>
        {{-- <div class="tab-pane fade" id="poas" role="tabpanel" aria-labelledby="poas-tab">
            <h4>Выданные доверенности</h4>
            <div class="row m-1">
                <div class="col-md-12">
                    @include('Admin.companies.components.poas-table')
                </div>
            </div>
        </div> --}}
        <div class="tab-pane fade" id="bankAccounts" role="tabpanel" aria-labelledby="bankAccounts-tab">
            <h4>Банковские счета</h4>
            @include('companies.components.bank-accounts-table')
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

        document.addEventListener("DOMContentLoaded", autoSelectPage);
    </script>

@endsection

@extends('layouts.big-form')

@section('title')
    Администратор|Карточка договора
@endsection

@section('content')
    <h3>Карточка договора № {{$agreement->agr_number}}
        от {{\Carbon\Carbon::parse($agreement->date_open)->format('d.m.Y')}}</h3>


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
                <i class="bi bi-bank"></i>

                Основная информация
            </button>
            <button class="nav-link"
                    id="documents-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#documents"
                    type="button"
                    role="tab"
                    aria-controls="documents"
                    aria-selected="true">
                <i class="bi bi-files"></i>
                Файлы/документы
            </button>
           
            <button class="nav-link"
                    id="collaterals-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#collaterals"
                    type="button"
                    role="tab"
                    aria-controls="collaterals"
                    aria-selected="true">
                <i class="bi bi-file-earmark-lock"></i>
                Залог
            </button>
            <button class="nav-link"
                    id="guarantees-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#guarantees"
                    type="button"
                    role="tab"
                    aria-controls="guarantees"
                    aria-selected="true">
                <i class="bi bi-sign-merge-left"></i>
                Поручительства
            </button>

            @if(Gate::allows('s-accruals'))
            <button class="nav-link"
                    id="accruals-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#accruals"
                    type="button"
                    role="tab"
                    aria-controls="accruals"
                    aria-selected="true">
                <i class="bi bi-minecart-loaded" aria-hidden="true"></i>
                Начисления
            </button>
            @endif

            @if(Gate::allows('s-payments'))
            <button class="nav-link"
                    id="payments-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#payments"
                    type="button"
                    role="tab"
                    aria-controls="payments"
                    aria-selected="true">
                <i class="bi bi-cash-coin"></i>
                Платежи
            </button>
            @endif
            {{-- <button class="nav-link"
                    id="tasks-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#tasks"
                    type="button"
                    role="tab"
                    aria-controls="tasks"
                    aria-selected="true">
                <i class="fa fa-tasks" aria-hidden="true"></i>
                Задачи по договору
            </button> --}}
            <button class="nav-link"
                    id="notes-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#notes"
                    type="button"
                    role="tab"
                    aria-controls="notes"
                    aria-selected="true">
                <i class="bi bi-chat-left-dots"></i>
                Заметки
            </button>

            {{-- Это только для тех, кто может редактировать платежи => может управлять ключевыфми словами --}}
            @if(Gate::allows('e-payments'))
            <button class="nav-link"
                    id="keywords-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#keywords"
                    type="button"
                    role="tab"
                    aria-controls="keywords"
                    aria-selected="true">
                <i class="bi bi-key"></i>
                Ключевые слова
            </button>
            @endif

        </div>
    </nav>


    <div class="tab-content p-2" id="nav-tabContent">
        <div class="tab-pane fade show active" id="main" role="tabpanel" aria-labelledby="main-tab">
            <h4>Основные данные</h4>
            @include('agreements.agreement-summary.agreement-main')
        </div>
        
        <div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="documents-tab">
            <h4>Связанные файлы</h4>
            <div class="row m-1">
                <div class="col-md-12">
                    <a class="btn btn-outline-primary"
                       href="{{route('addAgreementDocument',['agreement' => $agreement])}}">
                        Добавить документ
                    </a>
                    @include('agreements.agreement-summary.agreement-files')
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="collaterals" role="tabpanel" aria-labelledby="collaterals-tab">
            <h4>Техника в залоге по договору</h4>
            <div class="row m-1">
                <div class="col-md-12">
                    {{-- <a class="btn btn-outline-info"
                       href="{{route('admin.addDeposit', ['agreement'=>$agreement])}}">Добавить залоговую технику
                    </a> --}}
                    {{-- @include('Admin.agreements.agreement-summary.agreement-deposits', ['deposits' => $agreement->deposites]) --}}
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="guarantees" role="tabpanel" aria-labelledby="guarantees-tab">
            <h4>Полученные поручительства по договору</h4>
            <div class="row m-1">
                <div class="col-md-12">
                    {{-- <a class="btn btn-outline-info"
                       href="{{route('admin.addGuaranteeLE', ['agreement'=>$agreement])}}">
                        Добавить поручительство компании
                    </a> --}}
                    {{-- @include('Admin.agreements.agreement-summary.agreement-guarantees', ['guarantees' => $agreement->guarantees]) --}}
                </div>
            </div>
        </div>


        @if(Gate::allows('s-accruals'))
        <div class="tab-pane fade" id="accruals" role="tabpanel" aria-labelledby="accruals-tab">
            <div class="row">
                @include('agreements.agreement-summary.agreement-accruals', ['accruals' =>$agreement->accruals])
            </div>
        </div>
        @endif


        @if(Gate::allows('s-payments'))
        <div class="tab-pane fade" id="payments" role="tabpanel" aria-labelledby="payments-tab">
            <div class="row">
                @include('agreements.agreement-summary.payment-tables', ['payments' =>$agreement->payments->sortBy('payment_date')])
                {{-- @include('Admin.agreements.agreement-summary.real-payment-tables', ['realPayments' =>$agreement->realPayments->sortBy('payment_date')]) --}}
            </div>
        </div>
        @endif

        {{-- <div class="tab-pane fade" id="tasks" role="tabpanel" aria-labelledby="tasks-tab">
            <h4>Задачи по договору </h4>
             @include('Admin.agreements.agreement-summary.agreement-tasks') 
        </div> --}}

        <div class="tab-pane fade" id="notes" role="tabpanel" aria-labelledby="notes-tab">
            <h4>Заметки по договору </h4>
            @include('agreements.agreement-summary.agreement-notes')
        </div>

        @if(Gate::allows('e-real_payment'))        
        <div class="tab-pane fade" id="keywords" role="tabpanel" aria-labelledby="keywords-tab">
            <h4>Ключевые слова (синонимы) для идентификации платежей по договору </h4>
            <div class="row">
                {{-- <div class="col-md-12">
                    <a class="btn btn-outline-info"
                    href="{{route('admin.addAgreementKeyword', ['agreement'=>$agreement])}}">Новое ключевое слово</a>

                </div>
            </div>
            @include('Admin.agreements.agreement-summary.agreement-keywords', ['keywords' =>$agreement->keywords->sortBy('name')]) --}}
        </div>
        @endif


    </div>

@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Подключаем jQuery -->
    <script>
        function autoSelectPage() {
            let urlArr = document.location.pathname.split('/')
            if (urlArr.length ===5) {
                let tabName = '[data-bs-target="#' + urlArr[urlArr.length-1] + '"'
                $(tabName).tab('show')
            }
        }

        document.addEventListener("DOMContentLoaded", autoSelectPage);
    </script>

@endsection

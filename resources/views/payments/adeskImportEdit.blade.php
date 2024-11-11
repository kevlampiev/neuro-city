@extends('layouts.big-form') 

@section('title')
    Редактирование платежа
@endsection

@section('content')
    <h3> 
        @if ($model->adesk_id)
            Изменение импортированного платежа на 
            <span class="{{ ($model->adesk_type_operation_code == 1) ? 'text-success' : 'text-danger' }}">
                {{ ($model->adesk_type_operation_code == 1) ? 'приход' : 'РАСХОД' }} № {{ $model->adesk_id }}
            </span>    
        @else
            Добавление платежа
        @endif
    </h3>
    <form method="POST" enctype="multipart/form-data" 
        action="{{ route('import.adesk.payments.update', ['adesk_id' => $model->adesk_id]) }}" 
        id="payment-form">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-12">
                <input type="hidden" name="adesk_id" value="{{ $model->adesk_id }}">
                <input type="hidden" name="adesk_type_operation_code" value="{{ $model->adesk_type_operation_code }}">

                <div class="form-group">
                    <label for="inputDateOpen">Дата платежа</label>
                    <input type="date" class="form-control" id="inputDateOpen" 
                           name="date_open" value="{{ $model->date_open }}">
                </div>

                @include('partials.error', ['field' => 'date_open'])

                <div class="form-group">
                    <label for="bank">Банковский счет 
                        <span class="text-secondary fst-italic">( данные ADеsk: {{ $model->adesk_bank_name }} )</span> 
                    </label>
                    <select name="bank_account_id" class="form-control" id="bank">
                             <option value="" {{ $model->bank_account_id == null ? 'selected' : '' }}>
                                *НЕ ОПРЕДЕЛЕН*
                            </option>
                        @foreach ($accounts as $account)
                            <option value="{{ $account->id }}" {{ $model->bank_account_id == $account->id ? 'selected' : '' }}>
                                Владелец: {{ $account->owner->name }} [Банк: {{ $account->bank->name }}, Р.сч: {{ $account->account_number }}]
                            </option>
                        @endforeach
                    </select>
                </div>

                @include('partials.error', ['field' => 'bank_account_id'])

                <div class="form-group">
                    <label for="agreement">Номер договора {{$model->agreement_id}}</label>
                        <select name="agreement_id" class="form-control" id="agreement">
                            <option value="" {{ $model->agreement_id == null ? 'selected' : '' }}>
                                *НЕ ОПРЕДЕЛЕН*
                            </option>
                            @foreach ($agreements as $agreement)
                                <option value="{{ $agreement->id }}" {{ $model->agreement_id == $agreement->id ? 'selected' : '' }}>
                                    {{ $agreement->id }} {{ $agreement->name }} № {{ $agreement->agr_number }} от {{ $agreement->date_open }}
                                    между {{ $agreement->buyer->name }} и {{ $agreement->seller->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                @include('partials.error', ['field' => 'agreement_id'])

                <div class="form-group">
                    <label for="inputAmount">Сумма</label>
                    <input type="text" class="form-control" id="inputAmount" name="amount" value="{{ $model->amount }}">
                </div>

                @include('partials.error', ['field' => 'amount'])

                <div class="form-group">
                    <label for="inputVAT">В т.ч. НДС</label>
                    <input type="text" class="form-control" id="inputVAT" name="VAT" value="{{ $model->VAT }}">
                </div>
                @include('partials.error', ['field' => 'VAT'])

                <div class="form-group">
                    <label for="project_id">Проект 
                        <span class="text-secondary fst-italic">( данные ADеsk: {{ $model->adesk_project_name ?? '--' }} )</span> 
                    </label>
                    <select name="project_id" class="form-control" id="project_id">
                        <option value="" {{ $model->project_id == null ? 'selected' : '' }}>*БЕЗ ПРОЕКТА*</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}" {{ $model->project_id == $project->id ? 'selected' : '' }}>
                                {{ $project->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @include('partials.error', ['field' => 'project_id'])

                <div class="form-group">
                    <label for="cfs_item_id">Статья ОДДС
                        <span class="text-secondary fst-italic">( данные ADеsk: {{ $model->adesk_cfs_category_name ?? '--' }} )</span> 
                    </label>
                    <select name="cfs_item_id" class="form-control" id="cfs_item_id">
                        <option value="" {{ $model->cfs_item_id == null ? 'selected' : '' }}>*СТАТЬЯ НЕ ОПРЕДЕЛЕНА*</option>
                        @foreach ($cfsItems as $item)
                            <option value="{{ $item->id }}" {{ $model->cfs_item_id == $item->id ? 'selected' : '' }}>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="beneficiary_id">В пользу кого осуществелен платеж (если плательщик не является стороной по договору)</label>
                    <select name="beneficiary_id" class="form-control" id="beneficiary_id">

                        @foreach ($beneficiaries as $beneficiary)
                            <option value="{{ $beneficiary->id }}">{{ $beneficiary->name }}</option>
                        @endforeach
                    </select>
                </div>

                @include('partials.error', ['field' => 'beneficiary_id'])

                <div class="form-group">
                    <label for="description">Описание</label>
                    <textarea id="description" class="form-control" placeholder="Введите описание" name="description">{{ $model->description }}</textarea>
                </div>


                @include('partials.error', ['field' => 'description'])

                <ul class="p-0 m-0">
                    @foreach($errors->all() as $error)
                        <li class="m-0 p-0">{{ $error }}</li>
                    @endforeach
                </ul>

                <div class="row mt-3">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            @if ($model->id)
                                Изменить
                            @else
                                Добавить
                            @endif
                        </button>
                        <a class="btn btn-secondary" href="{{ route('import.adesk.payments.index') }}">Отмена</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

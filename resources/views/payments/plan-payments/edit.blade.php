
@extends('layouts.big-form')

@section('title', $planPayment->id ? 'Редактировать платеж' : 'Создать платеж')

@section('content')
<div class="container">
    <h1 class="mb-4">{{ $planPayment->id ? 'Редактировать плановый платеж' : 'Создать плановый платеж' }}</h1>

    <form method="POST">
        @csrf

        {{-- Agreement ID --}}
        <div class="form-group mb-3">
            <label for="agreement_id">Договор</label>
            <select id="agreement_id" name="agreement_id" class="form-control">
                <option value="">Выберите договор</option>
                @foreach($agreements as $agreement)
                    <option value="{{ $agreement['id'] }}" 
                        {{ old('agreement_id', $planPayment->agreement_id ?? '') == $agreement['id'] ? 'selected' : '' }}>
                        {{ $agreement['name'] }}
                    </option>
                @endforeach
            </select>
            @include('partials.error', ['field' => 'agreement_id'])
        </div>

        {{-- Project ID --}}
        <div class="form-group mb-3">
            <label for="project_id">Проект</label>
            <select id="project_id" name="project_id" class="form-control">
                <option value="">Выберите проект</option>
                @foreach($projects as $project)
                    <option value="{{ $project['id'] }}" 
                        {{ old('project_id', $planPayment->project_id ?? '') == $project['id'] ? 'selected' : '' }}>
                        {{ $project['name'] }}
                    </option>
                @endforeach
            </select>
            @include('partials.error', ['field' => 'project_id'])
        </div>

        {{-- CFS Item ID --}}
        <div class="form-group mb-3">
            <label for="cfs_item_id">Статья ОФР</label>
            <select id="cfs_item_id" name="cfs_item_id" class="form-control">
                <option value="">Выберите статью ОФР</option>
                @foreach($cfsItems as $cfsItem)
                    <option value="{{ $cfsItem['id'] }}" 
                        {{ old('cfs_item_id', $planPayment->cfs_item_id ?? '') == $cfsItem['id'] ? 'selected' : '' }}>
                        {{ $cfsItem['name'] }}
                    </option>
                @endforeach
            </select>
            @include('partials.error', ['field' => 'cfs_item_id'])
        </div>

        {{-- Initial Date and Shifted Date in one row --}}
        <div class="row mb-3">
            {{-- Initial Date --}}
            <div class="col-md-6">
                <div class="form-group">
                    <label for="initial_date">Первоначальная дата</label>
                    <input 
                        type="date" 
                        id="initial_date" 
                        class="form-control @error('initial_date') is-invalid @enderror" 
                        name="initial_date" 
                        value="{{ old('initial_date', $planPayment->initial_date ?? '') }}">
                    @include('partials.error', ['field' => 'initial_date'])
                </div>
            </div>

            {{-- Shifted Date --}}
            <div class="col-md-6">
                <div class="form-group">
                    <label for="shifted_date">Планируемая дата</label>
                    <input 
                        type="date" 
                        id="shifted_date" 
                        class="form-control @error('shifted_date') is-invalid @enderror" 
                        name="shifted_date" 
                        value="{{ old('shifted_date', $planPayment->shifted_date ?? '') }}">
                    @include('partials.error', ['field' => 'shifted_date'])
                </div>
            </div>
        </div>


        {{-- Amount and VAT --}}
        <div class="row mb-3">
            {{-- Amount --}}
            <div class="col-md-6">       
                <div class="form-group mb-3">
                    <label for="amount">Сумма</label>
                    <input 
                        type="number" 
                        step="0.01" 
                        id="amount" 
                        class="form-control @error('amount') is-invalid @enderror" 
                        name="amount" 
                        value="{{ old('amount', $planPayment->amount ?? '') }}">
                    @include('partials.error', ['field' => 'amount'])
                </div>
            </div>    

            {{-- VAT --}}
            <div class="col-md-6"> 
                <div class="form-group mb-3">
                    <label for="VAT">НДС</label>
                    <div class="input-group">
                        <input 
                            type="number" 
                            step="0.01" 
                            id="VAT" 
                            class="form-control @error('VAT') is-invalid @enderror" 
                            name="VAT" 
                            value="{{ old('VAT', $planPayment->VAT ?? '') }}">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            Выберите НДС
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#" data-vat="0">Без НДС</a></li>
                            <li><a class="dropdown-item" href="#" data-vat="10">10%</a></li>
                            <li><a class="dropdown-item" href="#" data-vat="20">20%</a></li>
                        </ul>
                    </div>
                    @include('partials.error', ['field' => 'VAT'])
                </div>
            </div>    
        </div>
        
        {{-- Description --}}
        <div class="form-group mb-3">
            <label for="description">Описание</label>
            <input type="text" 
                id="description" 
                class="form-control @error('description') is-invalid @enderror" 
                name="description"
                value="{{ old('description', $planPayment->description ?? '') }}">
            @include('partials.error', ['field' => 'description'])
        </div>

        <button type="submit" class="btn btn-primary">
            {{ $planPayment->id ? 'Сохранить' : 'Создать' }}
        </button>
        <a href="{{ route('agreementSummary', ['agreement'=>$planPayment->agreement_id, 'page'=>'payments']) }}" class="btn btn-secondary">Отмена</a>
    </form>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Инициализация Choices.js
        const selects = ['agreement_id', 'project_id', 'cfs_item_id'];
        selects.forEach(selectId => {
            const element = document.getElementById(selectId);
            new Choices(element, {
                searchEnabled: true,
                itemSelectText: '',
                removeItemButton: true,
            });
        });

        // Автозаполнение shifted_date
        const initialDateInput = document.getElementById('initial_date');
        const shiftedDateInput = document.getElementById('shifted_date');

        initialDateInput.addEventListener('input', () => {
            if (!shiftedDateInput.value) {
                shiftedDateInput.value = initialDateInput.value;
            }
        });

        // Обработка выбора НДС
        const vatInput = document.getElementById('VAT');
        const amountInput = document.getElementById('amount');
        document.querySelectorAll('.dropdown-item[data-vat]').forEach(item => {
            item.addEventListener('click', (event) => {
                event.preventDefault();
                const vatPercentage = parseFloat(item.getAttribute('data-vat'));
                const amount = parseFloat(amountInput.value) || 0;

                if (vatPercentage === 0) {
                    vatInput.value = 0; // Без НДС
                } else if (vatPercentage === 10) {
                    vatInput.value = (amount / 11).toFixed(2); // 10%
                } else if (vatPercentage === 20) {
                    vatInput.value = (amount / 6).toFixed(2); // 20%
                }
            });
        });
    });
</script>
@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
@endsection

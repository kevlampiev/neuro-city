@extends('layouts.big-form')

@section('title', $planAccrual->id ? 'Редактировать начисление' : 'Создать начисление')

@section('content')
<div class="container">
    <h1 class="mb-4">{{ $planAccrual->id ? 'Редактировать начисление' : 'Создать начисление' }}</h1>

    <form method="POST">
        @csrf
        @if($planAccrual->id)
            @method('PUT')
        @endif

        {{-- Agreement ID --}}
        <div class="form-group mb-3">
            <label for="agreement_id">Договор</label>
            <select id="agreement_id" name="agreement_id" class="form-control">
                <option value="">Выберите договор</option>
                @foreach($agreements as $agreement)
                    <option value="{{ $agreement['id'] }}" 
                        {{ old('agreement_id', $planAccrual->agreement_id ?? '') == $agreement['id'] ? 'selected' : '' }}>
                        {{ $agreement['name'] }}
                    </option>
                @endforeach
            </select>
            @include('partials.error', ['field' => 'agreement_id'])
        </div>

        {{-- Product ID --}}
        <div class="form-group mb-3">
            <label for="product_id">Продукт</label>
            <select id="product_id" name="product_id" class="form-control">
                <option value="">Выберите продукт</option>
                @foreach($products as $product)
                    <option value="{{ $product['id'] }}" 
                        {{ old('product_id', $planAccrual->product_id ?? '') == $product['id'] ? 'selected' : '' }}>
                        {{ $product['name'] }}
                    </option>
                @endforeach
            </select>
            @include('partials.error', ['field' => 'product_id'])
        </div>

        {{-- Project ID --}}
        <div class="form-group mb-3">
            <label for="project_id">Проект</label>
            <select id="project_id" name="project_id" class="form-control">
                <option value="">Выберите проект</option>
                @foreach($projects as $project)
                    <option value="{{ $project['id'] }}" 
                        {{ old('project_id', $planAccrual->project_id ?? '') == $project['id'] ? 'selected' : '' }}>
                        {{ $project['name'] }}
                    </option>
                @endforeach
            </select>
            @include('partials.error', ['field' => 'project_id'])
        </div>

        {{-- Units Count and Amount Per Unit --}}
        <div class="form-group mb-3">
            <label>Количество, стоимость и итог</label>
            <div class="row">
                <div class="col-md-4">
                    <input 
                        type="number" 
                        step="1" 
                        id="units_count" 
                        class="form-control @error('units_count') is-invalid @enderror" 
                        name="units_count" 
                        value="{{ old('units_count', $planAccrual->units_count ?? '') }}" 
                        placeholder="Количество">
                    @include('partials.error', ['field' => 'units_count'])
                </div>
                <div class="col-md-4">
                    <input 
                        type="number" 
                        step="0.01" 
                        id="amount_per_unit" 
                        class="form-control @error('amount_per_unit') is-invalid @enderror" 
                        name="amount_per_unit" 
                        value="{{ old('amount_per_unit', $planAccrual->amount_per_unit ?? '') }}" 
                        placeholder="Цена за единицу">
                    @include('partials.error', ['field' => 'amount_per_unit'])
                </div>
                <div class="col-md-4">
                    <input 
                        type="text" 
                        id="total_amount" 
                        class="form-control" 
                        value="{{ old('units_count', $planAccrual->units_count ?? 0) * old('amount_per_unit', $planAccrual->amount_per_unit ?? 0) }}" 
                        readonly 
                        placeholder="Итоговая сумма">
                </div>
            </div>
        </div>

        {{-- Initial and Shifted Dates --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="initial_date">Первоначальная дата</label>
                    <input 
                        type="date" 
                        id="initial_date" 
                        class="form-control @error('initial_date') is-invalid @enderror" 
                        name="initial_date" 
                        value="{{ old('initial_date', $planAccrual->initial_date ?? '') }}">
                    @include('partials.error', ['field' => 'initial_date'])
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="shifted_date">Планируемая дата</label>
                    <input 
                        type="date" 
                        id="shifted_date" 
                        class="form-control @error('shifted_date') is-invalid @enderror" 
                        name="shifted_date" 
                        value="{{ old('shifted_date', $planAccrual->shifted_date ?? '') }}">
                    @include('partials.error', ['field' => 'shifted_date'])
                </div>
            </div>
        </div>

        {{-- PL Item ID --}}
        <div class="form-group mb-3">
            <label for="pl_item_id">Статья PL</label>
            <select id="pl_item_id" name="pl_item_id" class="form-control">
                <option value="">Выберите статью</option>
                @foreach($plItems as $plItem)
                    <option value="{{ $plItem['id'] }}" 
                        {{ old('pl_item_id', $planAccrual->pl_item_id ?? '') == $plItem['id'] ? 'selected' : '' }}>
                        {{ $plItem['name'] }}
                    </option>
                @endforeach
            </select>
            @include('partials.error', ['field' => 'pl_item_id'])
        </div>

        {{-- Description --}}
        <div class="form-group mb-3">
            <label for="description">Описание</label>
            <input type="text" 
                id="description" 
                class="form-control @error('description') is-invalid @enderror" 
                name="description"
                value="{{ old('description', $planAccrual->description ?? '') }}">
            @include('partials.error', ['field' => 'description'])
        </div>

        <button type="submit" class="btn btn-primary">
            {{ $planAccrual->id ? 'Сохранить' : 'Создать' }}
        </button>
        <a href="{{ route('agreementSummary', ['agreement' => $planAccrual->agreement_id, 'page' => 'accruals']) }}" class="btn btn-secondary">Отмена</a>
    </form>
</div>
@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script>

document.addEventListener('DOMContentLoaded', () => {
    const unitsCountInput = document.getElementById('units_count');
    const amountPerUnitInput = document.getElementById('amount_per_unit');
    const totalAmountInput = document.getElementById('total_amount');
    const initialDateInput = document.getElementById('initial_date');
    const shiftedDateInput = document.getElementById('shifted_date');

    function updateTotalAmount() {
        const unitsCount = parseFloat(unitsCountInput.value) || 0;
        const amountPerUnit = parseFloat(amountPerUnitInput.value) || 0;
        totalAmountInput.value = (unitsCount * amountPerUnit).toFixed(2);
    }

    function updateShiftedDate() {
        if (!shiftedDateInput.value && initialDateInput.value) {
            shiftedDateInput.value = initialDateInput.value;
        }
    }

    unitsCountInput.addEventListener('input', updateTotalAmount);
    amountPerUnitInput.addEventListener('input', updateTotalAmount);
    initialDateInput.addEventListener('blur', updateShiftedDate);

    // Enable Choices.js on select elements
    const agreementSelect = new Choices('#agreement_id', { searchEnabled: true });
    const productSelect = new Choices('#product_id', { searchEnabled: true });
    const projectSelect = new Choices('#project_id', { searchEnabled: true });
    const plItemSelect = new Choices('#pl_item_id', { searchEnabled: true });
});
</script>
@endsection

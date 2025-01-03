@extends('layouts.big-form')

@section('title')
    Редактирование типа продукта/услуги
@endsection

@section('content')
    <h3>
        @if ($model->id)
            Изменение типа
        @else
            Добавление типа
        @endif
    </h3>
    <form method="POST" enctype="multipart/form-data" 
        action="{{ $model->id ? route('products.update', ['id' => $model->id]) : route('products.store') }}">
        @csrf
        @if ($model->id)
            @method('PUT')
        @endif

        <input type="hidden" name="id" value="{{ $model->id }}">

        <div class="row">
            <div class="col-md-12">
                <!-- Наименование продукта/услуги -->
                <div class="form-group">
                    <label for="inputName" class="form-label">Наименование продукта/услуги</label>
                    <input type="text" 
                           class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" 
                           id="inputName" 
                           placeholder="Введите название" 
                           name="name" 
                           value="{{ old('name', $model->name) }}">
                    @include('partials.error', ['field' => 'name'])
                </div>

                <!-- Скрытое поле для неотмеченного чекбокса -->
                <input type="hidden" name="is_service" value="0">

                <!-- Переключатель для is_service -->
                <div class="form-group mt-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input {{ $errors->has('is_service') ? 'is-invalid' : '' }}" 
                               type="checkbox" 
                               id="isService" 
                               name="is_service" 
                               value="1" 
                               {{ old('is_service', $model->is_service) ? 'checked' : '' }}>
                        <label class="form-check-label" for="isService">Это услуга</label>
                    </div>
                    @include('partials.error', ['field' => 'is_service'])
                </div>

                <!-- Описание -->
                <div class="form-group mt-3">
                    <label for="description" class="form-label">Описание</label>
                    <textarea id="description"
                              class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}"
                              placeholder="Введите описание" 
                              name="description">{{ old('description', $model->description) }}</textarea>
                    @include('partials.error', ['field' => 'description'])
                </div>
            </div>
        </div>

        <!-- Кнопки -->
        <div class="row mt-4">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary">
                    @if ($model->id)
                        Изменить
                    @else
                        Добавить
                    @endif
                </button>
                <a class="btn btn-secondary" href="{{ route('products.index') }}">Отмена</a>
            </div>
        </div>
    </form>
@endsection

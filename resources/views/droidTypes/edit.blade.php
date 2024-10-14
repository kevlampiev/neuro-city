@extends('layouts.big-form')

@section('title')
    Редактирование типа дроида
@endsection

@section('content')
    <h3> @if ($model->id)
            Изменение типа
        @else
            Добавление типа
        @endif</h3>
    <form method="POST" enctype="multipart/form-data" 
        action="{{$model->id?route('droidTypes.update', ['id' => $model->id]):route('droidTypes.store')}}">
        @csrf
        @if ($model->id)
            @method('PUT')
        @endif
        <form>
            <div class="row">
                <div class="col-md-12">
                    <input type="hidden" name="id" value="{{$model->id}}">
                    <div class="form-group">
                        <label for="inputName">Название модели</label>
                        <input type="text" class="form-control {{$errors->has('name')?'is-invalid':''}}" id="inputName"
                               placeholder="Введите название" name="name"
                               value="{{$model->name}}">
                    </div>
                    @if ($errors->has('name'))
                        <div class="alert alert-danger">
                            <ul class="p-0 m-0">
                                @foreach($errors->get('name') as $error)
                                    <li class="m-0 p-0"> {{$error}}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="description">Описание</label>
                        <textarea id="description"
                                class="form-control {{$errors->has('description') ? 'is-invalid' : ''}}"
                                aria-describedby="descriptionHelp"
                                placeholder="Введите описание" 
                                name="description">{{ old('description', $model->description) }}</textarea>
                    </div>
                    @if ($errors->has('description'))
                        <div class="alert alert-danger">
                            <ul class="p-0 m-0">
                                @foreach($errors->get('description') as $error)
                                    <li class="m-0 p-0">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
            </div>

            <div class="row mt-3">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">
                        @if ($model->id)
                            Изменить
                        @else
                            Добавить
                        @endif
                    </button>
                    <a class="btn btn-secondary" href="{{route('projects.index')}}">Отмена</a>
                </div>
            </div>


        </form>

    </form>

@endsection

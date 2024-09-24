@extends('layouts.big-form')

@section('title')
    Редактирование проекта
@endsection

@section('content')
    <h3> @if ($model->id)
            Изменение проекта
        @else
            Добавление проекта
        @endif</h3>
    <form method="POST" enctype="multipart/form-data" 
        action="{{$model->id?route('projects.update', ['id' => $model->id]):route('projects.store')}}"">
        @csrf
        @if ($model->id)
            @method('PUT')
        @endif
        <form>
            <div class="row">
                <div class="col-md-12">
                    <input type="hidden" name="id" value="{{$model->id}}">
                    <div class="form-group">
                        <label for="inputName">Название проекта</label>
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
                        <label for="date_open">Дата начала</label>
                        <input type="date"
                               id="date_open"
                               class="form-control {{$errors->has('date_open')?'is-invalid':''}}"
                               aria-describedby="model"
                               placeholder="Введите дату открытия проекта" name="date_open"
                               value="{{$model->date_open}}">
                    </div>
                    @if ($errors->has('date_open'))
                        <div class="alert alert-danger">
                            <ul class="p-0 m-0">
                                @foreach($errors->get('date_open') as $error)
                                    <li class="m-0 p-0"> {{$error}}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="date_open">Дата окончания</label>
                        <input type="date"
                               id="date_close"
                               class="form-control {{$errors->has('date_close')?'is-invalid':''}}"
                               aria-describedby="model"
                               placeholder="Введите дату завершения" name="date_close"
                               value="{{$model->date_close}}">
                    </div>
                    @if ($errors->has('date_close'))
                        <div class="alert alert-danger">
                            <ul class="p-0 m-0">
                                @foreach($errors->get('date_close') as $error)
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

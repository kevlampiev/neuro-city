@extends('layouts.big-form')

@section('title')
    Администратор|Редактирование роли
@endsection

@section('content')
    <h3> @if ($role->id)
            Изменить роль
        @else
            Добавить роль
        @endif</h3>
    <form method="POST">
        @csrf
        <form>
            <div class="form-group">
                <label for="inputName">Название роли</label>
                <input type="text"
                       class="{{($errors->has('name')?'form-control is-invalid':'form-control')}}"
                       id="inputName" placeholder="Введите название" name="name"
                       value="{{$role->name}}">
            </div>
            @if($errors->has('name'))
                <div class="alert alert-danger">
                    <ul class="p-0 m-0">
                        @foreach($errors->get('name') as $error)
                            <li class="m-0 p-0"> {{$error}}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-group">
                <label for="slug">Код</label>
                <input type="text"
                       class="{{($errors->has('slug')?'form-control is-invalid':'form-control')}}"
                       id="slug" name="slug"
                       value="{{$role->slug}}">
            </div>
            @if($errors->has('slug'))
                <div class="alert alert-danger">
                    <ul class="p-0 m-0">
                        @foreach($errors->get('slug') as $error)
                            <li class="m-0 p-0"> {{$error}}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <button type="submit" class="btn btn-primary">
                @if ($role->slug)
                    Изменить
                @else
                    Добавить
                @endif
            </button>
            <a class="btn btn-secondary" href="{{route('roles')}}">Отмена</a>

        </form>

    </form>

@endsection

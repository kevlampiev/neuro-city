@extends('layouts.big-form')

@section('title')
    Изменение заметки
@endsection

@section('content')
    <h3> @if ($projectNote->id)
            Редактирование заметки
        @else
            Добавить заметку
        @endif</h3>
    <form method="POST">
        @csrf
            <div class="form-group">
                <label for="input-project">Проект</label>
                <input type="hidden"
                       id="project_id" name="project_id" value="{{$project->id}}">
                <input type="hidden"
                       id="note_id" name="id" value="{{$projectNote->id}}">       
                <input type="text"
                       id="input-project" name="project" value="{{$project->name}}" disabled>
                 @if ($errors->has('project_id'))
                    <div class="alert alert-danger">
                        <ul class="p-0 m-0">
                            @foreach($errors->get('project_id') as $error)
                                <li class="m-0 p-0"> {{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif       
            </div>

            <div class="form-group">
                <label for="description">Текст заметки</label>
                <textarea class="form-control {{$errors->has('description')?'is-invalid':''}}"
                          id="description"
                          rows="13" name="description">{{$projectNote->description}}</textarea>
            </div>
            @if ($errors->has('description'))
                <div class="alert alert-danger">
                    <ul class="p-0 m-0">
                        @foreach($errors->get('description') as $error)
                            <li class="m-0 p-0"> {{$error}}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <button type="submit" class="btn btn-primary">
                @if ($projectNote->id)
                    Изменить
                @else
                    Добавить
                @endif
            </button>
            <a class="btn btn-secondary"
               href="{{route('projects.summary',['project'=>$project, 'page' => 'notes'])}}">
                Отмена
            </a>
    </form>

@endsection

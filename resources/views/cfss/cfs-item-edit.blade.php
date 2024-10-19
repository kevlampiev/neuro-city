@extends('layouts.big-form')

@section('title')
    Администратор|Редактирование группы статей CFS
@endsection

@section('content')
    <h3> @if ($cfsItem->id)
            Редактирование группы статей CFS
        @else
            Добавить новую группу
        @endif</h3>
    <form method="POST">
        @csrf

            <div class="form-group">
                <label for="cfs_section">Группа статей</label>
                <select name="group_id"
                            id="cfsGroups"
                            class="form-control {{$errors->has('group_id')?'is-invalid':''}}"
                            aria-describedby="cfsGroups">
                        @foreach ($cfsGroups as $cfsGroup)
                            <option
                                value="{{$cfsGroup->id}}" {{($cfsGroup->id == $cfsItem->group_id) ? 'selected' : ''}}>
                                <i>
                                {{($cfsGroup->cfs_section=="operations")?"опер.деят-ть":''}}
                                {{($cfsGroup->cfs_section=="investment")?"инвест.деят-ть":''}}
                                {{($cfsGroup->cfs_section=="finance")?"фин.деят-ть":''}} </i>
                                 - {{$cfsGroup->name}}
                            </option>
                        @endforeach
                    </select>
            </div>

            <div class="form-group">
                <label for="inputName">Наименование статьи</label>
                <input type="text"
                       @if ($errors->has('name'))
                           class="form-control is-invalid"
                       @else
                           class="form-control"
                       @endif
                       id="inputName" placeholder="Введите название статьи" name="name"
                       value="{{$cfsItem->name}}">
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
                <label for="description">Комментарий</label>
                <textarea class="form-control {{$errors->has('description')?'is-invalid':''}}"
                            id="description"
                            rows="13" name="description">{{$cfsItem->description}}</textarea>
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
                @if ($cfsGroup->id)
                    Изменить
                @else
                    Добавить
                @endif
            </button>
            <a class="btn btn-secondary" href="{{route('cfsGroups')}}">Отмена</a>

    </form>

@endsection

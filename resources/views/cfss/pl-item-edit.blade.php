@extends('layouts.big-form')

@section('title')
    Редактирование группы статей PL
@endsection

@section('content')
    <h3> @if ($plItem->id)
            Редактирование группы статей PL
        @else
            Добавить новую группу
        @endif</h3>
    <form method="POST">
        @csrf

            <div class="form-group">
                <label for="pl_section">Группа статей</label>
                <select name="pl_item_group_id"
                            id="plGroups"
                            class="form-control {{$errors->has('pl_item_group_id')?'is-invalid':''}}"
                            aria-describedby="plGroups">
                        @foreach ($plGroups as $plGroup)
                            <option
                                value="{{$plGroup->id}}" {{($plGroup->id == $plItem->pl_item_group_id) ? 'selected' : ''}}>
                                <i>
                                {{($plGroup->pl_section=="sales")?"Выручка":''}}
                                {{($plGroup->pl_section=="cogs")?"Себестоимость":''}}
                                {{($plGroup->pl_section=="indirect_costs")?"Косвенные расходы":''}} 
                                {{($plGroup->pl_section=="DA")?"Амортизация":''}} 
                                {{($plGroup->pl_section=="interests")?"Проценты":''}} 
                                {{($plGroup->pl_section=="tax")?"Налог на прибыль":''}} 
                                </i>
                                 - {{$plGroup->name}}
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
                       value="{{$plItem->name}}">
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
                            rows="13" name="description">{{$plItem->description}}</textarea>
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
                @if ($plGroup->id)
                    Изменить
                @else
                    Добавить
                @endif
            </button>
            <a class="btn btn-secondary" href="{{route('plGroups')}}">Отмена</a>

    </form>

@endsection

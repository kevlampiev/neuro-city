@extends('layouts.big-form')

@section('title')
    Редактирование группы статей PL
@endsection

@section('content')
    <h3> @if ($plGroup->id)
            Редактирование группы статей PL
        @else
            Добавить новую группу
        @endif</h3>
    <form method="POST">
        @csrf

            <div class="form-group">
                <label for="pl_section">Раздел отчета о прибылях и убытках</label>
                <select class="form-control" id="pl_section" name="pl_section" value="{{$plGroup->pl_section}}">
                {{-- //'sales', 'cogs', 'total_production_costs', 'commercial_costs', 'management_costs','other_pl', 'DA', 'interests','tax' --}}
                    <option value="sales" {{$plGroup->pl_section=="sales"?'selected':''}}>Выручка</option>
                    <option value="cogs" {{$plGroup->pl_section=="cogs"?'selected':''}}>Себестоимость</option>
                    <option value="indirect_costs" {{$plGroup->pl_section=="indirect_costs"?'selected':''}}>Косвенные расходы</option>
                    <option value="interests" {{$plGroup->pl_section=="interests"?'selected':''}}>Проценты</option>
                    <option value="DA" {{$plGroup->pl_section=="DA"?'selected':''}}>Амортизация</option>
                    <option value="tax" {{$plGroup->pl_section=="tax"?'selected':''}}>Налог на прибыль</option>
                </select>
            </div>

            <div class="form-group">
                <label for="inputSegment">Приход/расход</label>
                <select class="form-control" id="inputSegment" name="direction" value="{{$plGroup->direction}}">
                    <option value="inflow" {{$plGroup->direction=="inflow"?'selected':''}}>Приход</option>
                    <option value="outflow" {{$plGroup->direction=="outflow"?'selected':''}}>Расход</option>
                </select>
            </div>


            <div class="form-group">
                <label for="inputType">Наименование группы</label>
                <input type="text"
                       @if ($errors->has('name'))
                           class="form-control is-invalid"
                       @else
                           class="form-control"
                       @endif
                       id="inputType" placeholder="Введите название группы" name="name"
                       value="{{$plGroup->name}}">
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
                <label for="inputType">Вес в отчете о прибылях и убытках (целое число)</label>
                <input type="number"
                       @if ($errors->has('weight'))
                           class="form-control is-invalid"
                       @else
                           class="form-control"
                       @endif
                       id="inputType" placeholder="Введите вес статьи (целое число больше 0)" name="weight"
                       value="{{$plGroup->weight}}">
            </div>
            @if($errors->has('weight'))
                <div class="alert alert-danger">
                    <ul class="p-0 m-0">
                        @foreach($errors->get('weight') as $error)
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

@extends('layouts.big-form')

@section('title')
    Администратор|Редактирование группы статей CFS
@endsection

@section('content')
    <h3> @if ($cfsGroup->id)
            Редактирование группы статей CFS
        @else
            Добавить новую группу
        @endif</h3>
    <form method="POST">
        @csrf

            <div class="form-group">
                <label for="cfs_section">Раздел отчета о движении денежных средств</label>
                <select class="form-control" id="cfs_section" name="cfs_section" value="{{$cfsGroup->cfs_section}}">
                    <option value="operations" {{$cfsGroup->cfs_section=="operations"?'selected':''}}>Операционная деятельность</option>
                    <option value="finance" {{$cfsGroup->cfs_section=="finance"?'selected':''}}>Финансовая деятельность</option>
                    <option value="investment" {{$cfsGroup->cfs_section=="investment"?'selected':''}}>Инвестиционная деятельность</option>
                </select>
            </div>

            <div class="form-group">
                <label for="inputSegment">Направление движения денег</label>
                <select class="form-control" id="inputSegment" name="direction" value="{{$cfsGroup->direction}}">
                    <option value="inflow" {{$cfsGroup->direction=="inflow"?'selected':''}}>Поступление ДС</option>
                    <option value="outflow" {{$cfsGroup->direction=="outflow"?'selected':''}}>Отток ДС</option>
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
                       value="{{$cfsGroup->name}}">
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
                <label for="inputType">Вес в отчете о движении денежных средств (целое число)</label>
                <input type="number"
                       @if ($errors->has('weight'))
                           class="form-control is-invalid"
                       @else
                           class="form-control"
                       @endif
                       id="inputType" placeholder="Введите вес статьи (целое число больше 0)" name="weight"
                       value="{{$cfsGroup->weight}}">
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
                @if ($cfsGroup->id)
                    Изменить
                @else
                    Добавить
                @endif
            </button>
            <a class="btn btn-secondary" href="{{route('cfsGroups')}}">Отмена</a>

    </form>

@endsection

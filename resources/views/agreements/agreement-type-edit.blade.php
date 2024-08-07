@extends('layouts.big-form')

@section('title')
    Администратор|Редактирование типа договора
@endsection

@section('content')
    <h3> @if ($agrType->id)
            Изменение типа договора
        @else
            Добавить новый тип договора
        @endif</h3>
    <form action="{{route($route, ['agrType'=>$agrType])}}" method="POST">
        @csrf
        <form>
            <div class="form-group">
                <label for="inputType">Наименование типа договора</label>
                <input type="text"
                       @if ($errors->has('name'))
                           class="form-control is-invalid"
                       @else
                           class="form-control"
                       @endif
                       id="inputType" placeholder="Введите название типа" name="name"
                       value="{{$agrType->name}}">
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


            {{-- <div class="form-group">
                <label for="inputDirection">Направление деятельности</label>
                <select class="form-control" id="inputDirection" name="direction" value="{{$agrType->direction}}">
                    <option value="buy" {{$agrType->direction=="buy"?'selected':''}}>Покупка товаров,работ,услуг</option>
                    <option value="sell" {{$agrType->direction=="sell"?'selected':''}}>Реализация</option>
                </select>
            </div> --}}

            <div class="form-group">
                <label for="inputSegment">Сфера деятельности</label>
                <select class="form-control" id="inputSegment" name="segment" value="{{$agrType->segment}}">
                    <option value="operations" {{$agrType->segment=="operations"?'selected':''}}>Операционная деятельность</option>
                    <option value="finance" {{$agrType->segment=="finance"?'selected':''}}>Финансы</option>
                    <option value="investitions" {{$agrType->segment=="investitions"?'selected':''}}>Инвестиции</option>
                </select>
            </div>

            <div class="m-5"> </div>

            <button type="submit" class="btn btn-primary">
                @if ($agrType->id)
                    Изменить
                @else
                    Добавить
                @endif
            </button>
            <a class="btn btn-secondary" href="{{route('agrTypes')}}">Отмена</a>

        </form>

    </form>

@endsection

@extends('layouts.big-form')

@section('title')
    Администратор|Редактирование компании
@endsection

@section('content')
    <h3> @if ($company->id)
            Редактирование компании
        @else
            Добавить новую
        @endif</h3>
    <form action="{{route($route, $company->id)}}" method="POST">
        @csrf

            <div class="form-group">
                <label for="inputName">Краткое наименование</label>
                <input type="text"
                       class="{{($errors->has('name')?'form-control is-invalid':'form-control')}}"
                       id="inputName" placeholder="Введите название" name="name"
                       value="{{$company->name}}">
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
                <label for="inputFullName">Полное наименование</label>
                <input type="text"
                       class="{{($errors->has('fullname')?'form-control is-invalid':'form-control')}}"
                       id="inputFullName" placeholder="Введите название" name="fullname"
                       value="{{$company->fullname}}">
            </div>
            @if($errors->has('fullname'))
                <div class="alert alert-danger">
                    <ul class="p-0 m-0">
                        @foreach($errors->get('full_name') as $error)
                            <li class="m-0 p-0"> {{$error}}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <input type="text" name="company_type" value="other" hidden>

            <div class="form-group">
                <label for="inputINN">ИНН</label>
                <input type="text"
                       class="{{($errors->has('inn')?'form-control is-invalid':'form-control')}}"
                       id="inputINN" placeholder="Введите ИНН" name="inn"
                       value="{{$company->inn}}">
            </div>
            @if($errors->has('inn'))
                <div class="alert alert-danger">
                    <ul class="p-0 m-0">
                        @foreach($errors->get('inn') as $error)
                            <li class="m-0 p-0"> {{$error}}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <div class="form-group">
                <label for="inputOGRN">ОГРН</label>
                <input type="text"
                       class="{{($errors->has('ogrn')?'form-control is-invalid':'form-control')}}"
                       id="inputOGRN" placeholder="Введите ИНН" name="ogrn"
                       value="{{$company->ogrn}}">
            </div>
            @if($errors->has('ogrn'))
                <div class="alert alert-danger">
                    <ul class="p-0 m-0">
                        @foreach($errors->get('ogrn') as $error)
                            <li class="m-0 p-0"> {{$error}}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <div class="form-group">
                <label for="established_date">Дата регистрации</label>
                <input type="date"
                       class="{{($errors->has('established_date')?'form-control is-invalid':'form-control')}}"
                       id="established_date" name="established_date"
                       value="{{$company->established_date}}">
            </div>
            @if($errors->has('established_date'))
                <div class="alert alert-danger">
                    <ul class="p-0 m-0">
                        @foreach($errors->get('established_date') as $error)
                            <li class="m-0 p-0"> {{$error}}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <div class="form-group">
                <label for="inputAdress">Почтовый адрес</label>
                <input type="text"
                       class="{{($errors->has('post_adress')?'form-control is-invalid':'form-control')}}"
                       placeholder="Введите почтовый адрес"
                       id="inputAdress" name="post_adress"
                       value="{{$company->post_adress}}">
            </div>
            @if($errors->has('post_adress'))
                <div class="alert alert-danger">
                    <ul class="p-0 m-0">
                        @foreach($errors->get('post_adress') as $error)
                            <li class="m-0 p-0"> {{$error}}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-group">
                <label for="inputHeader">Руководитель</label>
                <input type="text"
                       class="{{($errors->has('header')?'form-control is-invalid':'form-control')}}"
                       id="inputHeader" name="header"
                       placeholder="Введите должность и ФИО руководителя"
                       value="{{$company->header}}">
            </div>
            @if($errors->has('header'))
                <div class="alert alert-danger">
                    <ul class="p-0 m-0">
                        @foreach($errors->get('header') as $error)
                            <li class="m-0 p-0"> {{$error}}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-group">
                <label for="inputPhone">Контактный телефон</label>
                <input type="text"
                       class="{{($errors->has('phone')?'form-control is-invalid':'form-control')}}"
                       id="inputPhone" name="phone"
                       placeholder="+7 (XXX) XXX-XXXX"
                       value="{{$company->phone}}">
            </div>
            @if($errors->has('phone'))
                <div class="alert alert-danger">
                    <ul class="p-0 m-0">
                        @foreach($errors->get('phone') as $error)
                            <li class="m-0 p-0"> {{$error}}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class=" mb-5"> </div>
            
            <button type="submit" class="btn btn-primary">
                @if ($company->id)
                    Изменить
                @else
                    Добавить
                @endif
            </button>
            <a class="btn btn-secondary" href="{{route('companies')}}">Отмена</a>


    </form>

@endsection

@extends('layouts.big-form')

@section('title')
    Администратор|Редактирование контрагента
@endsection

@section('content')
    <h3> @if ($counterparty->id)
            Редактирование данных о контрагенте
        @else
            Добавить нового контрагента
        @endif</h3>
    <form action="{{route($route, $counterparty->id)}}" method="POST">
        @csrf

            <div class="form-group">
                <label for="inputName">Краткое наименование</label>
                <input type="text"
                       class="{{($errors->has('name')?'form-control is-invalid':'form-control')}}"
                       id="inputName" placeholder="Введите название" name="name"
                       value="{{$counterparty->name}}">
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
                       value="{{$counterparty->fullname}}">
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

            <div class="form-group">
                <label for="company_type">Тип контрагента </label> 
                <select name="company_type"
                        class="form-control {{$errors->has('company_type')?'is-invalid':''}}"
                        aria-describedby="company_type" id="company_type">
                    <option
                        value="bank" {{($counterparty->company_type == 'bank') ? 'selected' : ''}}>
                        Банк
                    </option>
                    <option
                        value="insurer" {{($counterparty->company_type == 'insurer') ? 'selected' : ''}}>
                        Страховая компания
                    </option>
                    <option
                        value="lessor" {{($counterparty->company_type == 'lessor') ? 'selected' : ''}}>
                        Лизинговая компания
                    </option>
                    <option
                        value="goverment" {{($counterparty->company_type == 'goverment') ? 'selected' : ''}}>
                        Государственное учреждение
                    </option>

                    <option
                        value="other" {{($counterparty->company_type == 'other') ? 'selected' : ''}}>
                        Прочее
                    </option>
                </select>
            </div>
            @if ($errors->has('company_id'))
                <div class="alert alert-danger">
                    <ul class="p-0 m-0">
                        @foreach($errors->get('company_id') as $error)
                            <li class="m-0 p-0"> {{$error}}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-group">
                <label for="inputINN">ИНН</label>
                <input type="text"
                       class="{{($errors->has('inn')?'form-control is-invalid':'form-control')}}"
                       id="inputINN" placeholder="Введите ИНН" name="inn"
                       value="{{$counterparty->inn}}">
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
                       value="{{$counterparty->ogrn}}">
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
                       value="{{$counterparty->established_date}}">
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
                       value="{{$counterparty->post_adress}}">
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
                <label for="inputEDO">Организация использует ЭДО</label>
                <input type="checkbox" id="scales" name="uses_edo" {{$counterparty->uses_edo?'checked':''}} />
            </div>

            <div class="form-group">
                <label for="inputHeader">Руководитель</label>
                <input type="text"
                       class="{{($errors->has('header')?'form-control is-invalid':'form-control')}}"
                       id="inputHeader" name="header"
                       placeholder="Введите должность и ФИО руководителя"
                       value="{{$counterparty->header}}">
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
                       value="{{$counterparty->phone}}">
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
                @if ($counterparty->id)
                    Изменить
                @else
                    Добавить
                @endif
            </button>
            <a class="btn btn-secondary" href="{{route('counterparties')}}">Отмена</a>


    </form>

@endsection

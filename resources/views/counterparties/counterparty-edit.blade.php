@extends('layouts.big-form')

@section('title', $counterparty->id ? 'Редактирование контрагента' : 'Добавить контрагента')

@section('content')
    <h3>{{ $counterparty->id ? 'Редактирование данных о контрагенте' : 'Добавить нового контрагента' }}</h3>

    <form action="{{route($route, $counterparty->id)}}" method="POST">
        @csrf
        <div class="row mb-3">    
            <div class="col-md-6">
                <div class="form-group">
                    <label for="inputName">Краткое наименование</label>
                    <input type="text"
                        class="{{($errors->has('name')?'form-control is-invalid':'form-control')}}"
                        id="inputName" placeholder="Введите название" name="name"
                        value="{{$counterparty->name}}">
                </div>
                @include('partials.error', ['field' => 'name'])

                <div class="form-group">
                    <label for="inputFullName">Полное наименование</label>
                    <input type="text"
                        class="{{($errors->has('fullname')?'form-control is-invalid':'form-control')}}"
                        id="inputFullName" placeholder="Введите название" name="fullname"
                        value="{{$counterparty->fullname}}">
                </div>
                @include('partials.error', ['field' => 'fullname'])

                <div class="form-group">
                    <label for="company_type">Тип контрагента </label> 
                    <select name="company_type"
                            class="form-control {{$errors->has('company_type')?'is-invalid':''}}"
                            aria-describedby="company_type" id="company_type">
                        <option value="bank" {{($counterparty->company_type == 'bank') ? 'selected' : ''}}> Банк </option>
                        <option value="insurer" {{($counterparty->company_type == 'insurer') ? 'selected' : ''}}> Страховая компания </option>
                        <option value="lessor" {{($counterparty->company_type == 'lessor') ? 'selected' : ''}}> Лизинговая компания </option>
                        <option value="goverment" {{($counterparty->company_type == 'goverment') ? 'selected' : ''}}>Государственное учреждение</option>
                        <option value="other" {{($counterparty->company_type == 'other') ? 'selected' : ''}}> Прочее </option>
                    </select>
                </div>
                @include('partials.error', ['field' => 'company_type'])
                

                <div class="form-group">
                    <label for="inputINN">ИНН</label>
                    <input type="text"
                        class="{{($errors->has('inn')?'form-control is-invalid':'form-control')}}"
                        id="inputINN" placeholder="Введите ИНН" name="inn"
                        value="{{$counterparty->inn}}">
                </div>
                @include('partials.error', ['field' => 'inn'])
                

                <div class="form-group">
                    <label for="inputOGRN">ОГРН</label>
                    <input type="text"
                        class="{{($errors->has('ogrn')?'form-control is-invalid':'form-control')}}"
                        id="inputOGRN" placeholder="Введите ИНН" name="ogrn"
                        value="{{$counterparty->ogrn}}">
                </div>
                @include('partials.error', ['field' => 'ogrn'])


                <div class="form-group">
                    <label for="established_date">Дата регистрации</label>
                    <input type="date"
                        class="{{($errors->has('established_date')?'form-control is-invalid':'form-control')}}"
                        id="established_date" name="established_date"
                        value="{{$counterparty->established_date}}">
                </div>
                @include('partials.error', ['field' => 'established_date'])
            </div>        

            <div class="col-md-6">

                <div class="form-group">
                    <label for="inputAdress">Почтовый адрес</label>
                    <input type="text"
                        class="{{($errors->has('post_adress')?'form-control is-invalid':'form-control')}}"
                        placeholder="Введите почтовый адрес"
                        id="inputAdress" name="post_adress"
                        value="{{$counterparty->post_adress}}">
                </div>
                @include('partials.error', ['field' => 'post_adress'])


                <div class="form-group form-check">
                    <input 
                        type="checkbox" 
                        class="form-check-input" 
                        id="uses_edo" 
                        name="uses_edo" 
                        {{ $counterparty->uses_edo ? 'checked' : '' }}
                    >
                    <label class="form-check-label" for="uses_edo">Организация использует ЭДО</label>
                </div>
                @include('partials.error', ['field' => 'uses_edo'])

                <div class="form-group">
                    <label for="inputHeader">Руководитель</label>
                    <input type="text"
                        class="{{($errors->has('header')?'form-control is-invalid':'form-control')}}"
                        id="inputHeader" name="header"
                        placeholder="Введите должность и ФИО руководителя"
                        value="{{$counterparty->header}}">
                </div>
                @include('partials.error', ['field' => 'header'])
                

                <div class="form-group">
                    <label for="inputPhone">Контактный телефон</label>
                    <input type="text"
                        class="{{($errors->has('phone')?'form-control is-invalid':'form-control')}}"
                        id="inputPhone" name="phone"
                        placeholder="+7 (XXX) XXX-XXXX"
                        value="{{$counterparty->phone}}">
                </div>
                @include('partials.error', ['field' => 'phone'])

            </div>
        </div>    
            
            
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

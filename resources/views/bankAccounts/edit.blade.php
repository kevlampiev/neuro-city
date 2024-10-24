@extends('layouts.big-form')

@section('title')
    Редактирование банковского счета
@endsection

@section('content')
    <h3> @if ($model->id)
            Изменение параметров счета
        @else
            Добавление счета
        @endif</h3>
    <form method="POST" enctype="multipart/form-data" 
        action="{{$model->id?route('accounts.update', ['bankAccount' => $model->id]):route('accounts.store')}}">
        @csrf
        @if ($model->id)
            @method('PUT')
        @endif
        <form>
            <div class="row">
                <div class="col-md-12">
                    <input type="hidden" name="id" value="{{$model->id}}">
                    <div class="form-group">
                        <label for="inputAccountNumber">Номер счета</label>
                        <input type="text" class="form-control {{$errors->has('account_number')?'is-invalid':''}}" id="inputAccountNumber"
                               placeholder="0000000000000000000" name="account_number"
                               value="{{$model->account_number}}">
                    </div>
                    @if ($errors->has('account_number'))
                        <div class="alert alert-danger">
                            <ul class="p-0 m-0">
                                @foreach($errors->get('account_number') as $error)
                                    <li class="m-0 p-0"> {{$error}}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    
                    <div class="form-group">
                        <label for="inputDateOpen">Дата открытия</label>
                        <input type="date" class="form-control {{$errors->has('date_open')?'is-invalid':''}}" id="inputDateOpen"
                               name="date_open"
                               value="{{$model->date_open}}">
                    </div>
                    @if ($errors->has('date_open'))
                        <div class="alert alert-danger">
                            <ul class="p-0 m-0">
                                @foreach($errors->get('date_open') as $error)
                                    <li class="m-0 p-0"> {{$error}}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="inputDateClose">Дата закрытия</label>
                        <input type="date" class="form-control {{$errors->has('date_close')?'is-invalid':''}}" id="inputDateClose"
                               name="date_close"
                               value="{{$model->date_close}}">
                    </div>
                    @if ($errors->has('date_close'))
                        <div class="alert alert-danger">
                            <ul class="p-0 m-0">
                                @foreach($errors->get('date_close') as $error)
                                    <li class="m-0 p-0"> {{$error}}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="inputOwner">Владелец счета</label>
                        <select name="owner_id"
                                class="form-control {{$errors->has('owner_id')?'is-invalid':''}}"
                                id="inputOwner">
                            @foreach ($owners as $owner)
                                <option
                                    value="{{$owner->id}}" {{($owner->id == $model->owner_id) ? 'selected' : ''}}>
                                    {{$owner->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @if ($errors->has('owner_id'))
                        <div class="alert alert-danger">
                            <ul class="p-0 m-0">
                                @foreach($errors->get('owner_id') as $error)
                                    <li class="m-0 p-0"> {{$error}}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif


                    <div class="form group">
                        <label for="bank">Банк</label>
                        <select name="bank_id"
                                class="form-control {{$errors->has('bank_id')?'is-invalid':''}}"
                                id="bank">
                            @foreach ($banks as $bank)
                                <option
                                    value="{{$bank->id}}" {{($bank->id == $model->bank_id) ? 'selected' : ''}}>
                                    {{$bank->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @if ($errors->has('bank_id'))
                        <div class="alert alert-danger">
                            <ul class="p-0 m-0">
                                @foreach($errors->get('bank_id') as $error)
                                    <li class="m-0 p-0"> {{$error}}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="description">Описание</label>
                        <textarea id="description"
                                class="form-control {{$errors->has('description') ? 'is-invalid' : ''}}"
                                aria-describedby="descriptionHelp"
                                placeholder="Введите описание" 
                                name="description">{{ old('description', $model->description) }}</textarea>
                    </div>
                    @if ($errors->has('description'))
                        <div class="alert alert-danger">
                            <ul class="p-0 m-0">
                                @foreach($errors->get('description') as $error)
                                    <li class="m-0 p-0">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
            </div>

            <div class="row mt-3">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">
                        @if ($model->id)
                            Изменить
                        @else
                            Добавить
                        @endif
                    </button>
                    <a class="btn btn-secondary" href="{{route('accounts.index')}}">Отмена</a>
                </div>
            </div>


        </form>

    </form>

@endsection

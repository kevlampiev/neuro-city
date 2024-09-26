@extends('layouts.big-form')

@section('title')
    Администратор|Редактирование договора
@endsection

@section('content')
    <h3> @if ($agreement->id)
            Изменение данных договора
        @else
            Добавить новый договор
        @endif</h3>
    <form  method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="name">Наименование договора</span>
                    <input type="text"
                           class="form-control {{$errors->has('name')?'is-invalid':''}}"
                           aria-describedby="name"
                           placeholder="Введите название договора" name="name"
                           value="{{$agreement->name}}">
                </div>
                @if ($errors->has('name'))
                    <div class="alert alert-danger">
                        <ul class="p-0 m-0">
                            @foreach($errors->get('name') as $error)
                                <li class="m-0 p-0"> {{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="input-group mb-3">
                    <span class="input-group-text" id="companies">Продавец </span>
                    <select name="seller_id"
                            class="form-control {{$errors->has('seller_id')?'is-invalid':''}}"
                            aria-describedby="sellers">
                        @foreach ($sellers as $seller)
                            <option
                                value="{{$seller->id}}" {{($seller->id == $agreement->seller_id) ? 'selected' : ''}}
                                class="{{$seller->our_company?'bg-warning':''}} {{(!$seller->adesk_id?'text-danger':'')}}">
                                {{$seller->name}}
                            </option>
                        @endforeach
                    </select>
                </div>
                @if ($errors->has('seller_id'))
                    <div class="alert alert-danger">
                        <ul class="p-0 m-0">
                            @foreach($errors->get('seller_id') as $error)
                                <li class="m-0 p-0"> {{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="input-group mb-3">
                    <span class="input-group-text" >Покупатель </span>
                    <select name="buyer_id"
                            id="buyers"
                            class="form-control {{$errors->has('buyer_id')?'is-invalid':''}}"
                            aria-describedby="buyers">
                        @foreach ($buyers as $buyer)
                            <option
                                value="{{$buyer->id}}" {{($buyer->id == $agreement->buyer_id) ? 'selected' : ''}}
                                    class="{{$buyer->our_company?'bg-warning':''}} {{(!$buyer->adesk_id?'text-danger':'')}}">
                                
                                {{$buyer->name}}
                            </option>
                        @endforeach
                    </select>
                </div>
                @if ($errors->has('buyer_id'))
                    <div class="alert alert-danger">
                        <ul class="p-0 m-0">
                            @foreach($errors->get('buyer_id') as $error)
                                <li class="m-0 p-0"> {{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="input-group mb-3">
                    <span class="input-group-text" id="agreementTypes">Тип договора </span>
                    <select name="agreement_type_id"
                            class="form-control {{$errors->has('agreement_type_id')?'is-invalid':''}}"
                            aria-describedby="agreementTypes">
                        @foreach ($agreementTypes as $type)
                            <option
                                value="{{$type->id}}" {{($type->id == $agreement->agreement_type_id) ? 'selected' : ''}}>
                                {{$type->name}}
                            </option>
                        @endforeach
                    </select>
                </div>
                @if ($errors->has('agreement_type_id'))
                    <div class="alert alert-danger">
                        <ul class="p-0 m-0">
                            @foreach($errors->get('agreement_type_id') as $error)
                                <li class="m-0 p-0"> {{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="input-group mb-3">
                    <span class="input-group-text" id="agr_number">Номер договора</span>
                    <input type="text"
                           class="form-control {{$errors->has('agr_number')?'is-invalid':''}}"
                           aria-describedby="agr_number"
                           placeholder="Введите номер договора" name="agr_number"
                           value="{{$agreement->agr_number}}">
                </div>
                @if ($errors->has('agr_number'))
                    <div class="alert alert-danger">
                        <ul class="p-0 m-0">
                            @foreach($errors->get('agr_number') as $error)
                                <li class="m-0 p-0"> {{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


               
                <div class="input-group mb-3">
                    <span class="input-group-text" id="date_open">Срок действия</span>
                    <input type="date"
                           class="form-control {{$errors->has('date_open')?'is-invalid':''}}"
                           aria-describedby="date_open"
                           placeholder="Дата заключения" name="date_open"
                           value="{{$agreement->date_open}}">
                    <input type="date"
                           class="form-control {{$errors->has('date_close')?'is-invalid':''}}"
                           aria-describedby="date_close"
                           placeholder="Планируемая дата окончания" name="date_close"
                           value="{{$agreement->date_close}}">
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
                @if ($errors->has('date_close'))
                    <div class="alert alert-danger">
                        <ul class="p-0 m-0">
                            @foreach($errors->get('date_close') as $error)
                                <li class="m-0 p-0"> {{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="input-group mb-3">
                    <span class="input-group-text" id="real_date_close">Реальная дата закрытия</span>
                    <input type="date"
                           class="form-control {{$errors->has('real_date_close')?'is-invalid':''}}"
                           aria-describedby="real_date_close"
                           placeholder="Реальная дата заершения" name="real_date_close"
                           value="{{$agreement->real_date_close}}">
                </div>
                @if ($errors->has('real_date_close'))
                    <div class="alert alert-danger">
                        <ul class="p-0 m-0">
                            @foreach($errors->get('real_date_close') as $error)
                                <li class="m-0 p-0"> {{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


                <div class="input-group mb-3">
                    <span class="input-group-text" id="project">Проект к которому относится договор </span>
                    <select name="project_id"
                            class="form-control {{$errors->has('project_id')?'is-invalid':''}}"
                            aria-describedby="projects" id="projects" 
                            {{-- style="display: max-widh:300px;" --}}
                            >
                            <option
                                value="" {{($agreement->project_id == null) ? 'selected' : ''}}>
                                * Без проекта
                            </option>
                        @foreach ($projects as $project)
                            <option
                                value="{{$project->id}}" {{($project->id == $agreement->project_id) ? 'selected' : ''}}>
                                {{$project->name}}
                            </option>
                        @endforeach
                    </select>
                </div>
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


            <div class="col-md-6 pl-3">

                <div class="form-group">
                    <label for="description">Комментарий</label>
                    <textarea class="form-control {{$errors->has('description')?'is-invalid':''}}"
                              id="description"
                              rows="16" name="description">{{$agreement->description}}</textarea>
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


            </div>
        </div>

        <button type="submit" class="btn btn-primary">
            @if ($agreement->id)
                Изменить
            @else
                Добавить
            @endif
        </button>
        <a class="btn btn-secondary" href="{{session('previous_url', route('agreements'))}}">Отмена</a>

    </form>

@endsection


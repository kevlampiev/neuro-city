@extends('layouts.admin')

@section('title')
    Администратор|Редактирование договора
@endsection

@section('content')
    <h3> @if ($agreement->id)
            Изменение данных договора
        @else
            Добавить новый договор
        @endif</h3>
    <form action="{{route($route, $agreement->id)}}" method="POST">
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
                    <span class="input-group-text" id="companies">Компания группы </span>
                    <select name="company_id"
                            class="form-control {{$errors->has('company_id')?'is-invalid':''}}"
                            aria-describedby="companies">
                        @foreach ($companies as $company)
                            <option
                                value="{{$company->id}}" {{($company->id == $agreement->company_id) ? 'selected' : ''}}>
                                {{$company->name}}
                            </option>
                        @endforeach
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

                <div class="input-group mb-3">
                    <span class="input-group-text" >Контрагент </span>
                    <select name="counterparty_id"
                            id="counterparties"
                            class="form-control {{$errors->has('counterparty_id')?'is-invalid':''}}"
                            aria-describedby="counterparties">
                        @foreach ($counterparties as $counterparty)
                            <option
                                value="{{$counterparty->id}}" {{($counterparty->id == $agreement->counterparty_id) ? 'selected' : ''}}>
                                {{$counterparty->name}}
                            </option>
                        @endforeach
                    </select>
                </div>
                @if ($errors->has('counterparty_id'))
                    <div class="alert alert-danger">
                        <ul class="p-0 m-0">
                            @foreach($errors->get('counterparty_id') as $error)
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

                @php $currencies = \Illuminate\Support\Facades\Config::get('constants.currencies') @endphp
                <div class="input-group mb-3">
                    <span class="input-group-text" id="principal_amount">Основной долг/стоимость имущества</span>
                    <input type="number" step="0.01" min="0"
                           class="form-control {{$errors->has('principal_amount')?'is-invalid':''}}"
                           aria-describedby="principal_amount"
                           placeholder="Основной долг/стоимость имущества (для лизингов)" name="principal_amount"
                           value="{{$agreement->principal_amount}}">
                    <select name="currency" id="currency"
                            class="form-control {{$errors->has('currency')?'is-invalid':''}}">
                        @foreach ($currencies as $currency)
                            <option value="{{$currency}}" {{($currency == $agreement->currency) ? 'selected' : ''}}>
                                {{$currency}}
                            </option>
                        @endforeach
                    </select>
                </div>
                @if ($errors->has('principal_amount'))
                    <div class="alert alert-danger">
                        <ul class="p-0 m-0">
                            @foreach($errors->get('principal_amount') as $error)
                                <li class="m-0 p-0"> {{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if ($errors->has('currency'))
                    <div class="alert alert-danger">
                        <ul class="p-0 m-0">
                            @foreach($errors->get('currency') as $error)
                                <li class="m-0 p-0"> {{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="input-group mb-3">
                    <span class="input-group-text" id="interest_rate">Процентная ставка (% годовых)</span>
                    <input type="number" step="0.01" min="0" placeholder="0,00"
                           class="form-control {{$errors->has('interest_rate')?'is-invalid':''}}"
                           aria-describedby="interest_rate"
                           name="interest_rate"
                           value="{{$agreement->interest_rate}}">
                </div>
                @if ($errors->has('interest_rate'))
                    <div class="alert alert-danger">
                        <ul class="p-0 m-0">
                            @foreach($errors->get('interest_rate') as $error)
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

            </div>

            <div class="col-md-6 pl-3">



                <div class="input-group mb-3">
                    <span class="input-group-text" id="VAT">Ставка НДС, %</span>
                    <input type="number" step="0.01" min="0"
                           class="form-control {{$errors->has('VAT')?'is-invalid':''}}"
                           aria-describedby="VAT"
                           name="VAT"
                           value="{{$agreement->VAT}}">
                </div>
                @if ($errors->has('VAT'))
                    <div class="alert alert-danger">
                        <ul class="p-0 m-0">
                            @foreach($errors->get('VAT') as $error)
                                <li class="m-0 p-0"> {{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


                <div class="input-group mb-3">
                    <span class="input-group-text" id="payment_term">Срок оплаты (дней)</span>
                    <input type="number" step="1" min="0"
                           class="form-control {{$errors->has('payment_term')?'is-invalid':''}}"
                           aria-describedby="payment_term"
                           name="payment_term"
                           value="{{$agreement->payment_term}}">
                </div>
                @if ($errors->has('payment_term'))
                    <div class="alert alert-danger">
                        <ul class="p-0 m-0">
                            @foreach($errors->get('payment_term') as $error)
                                <li class="m-0 p-0"> {{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="input-group mb-3">
                    <span class="input-group-text" id="unitOfMeasurement">Единица измерения работ/услуг </span>
                    <select name="unit_of_measurement_id"
                            class="form-control {{$errors->has('unit_of_measurement_id')?'is-invalid':''}}"
                            aria-describedby="unitOfMeasurements">
                        @foreach ($unitOfMeasurements as $unit)
                            <option
                                value="{{$unit->id}}" {{($unit->id == $agreement->unit_of_measurement_id) ? 'selected' : ''}}>
                                {{$unit->name}}
                            </option>
                        @endforeach
                    </select>
                </div>
                @if ($errors->has('unit_of_measurement_id'))
                    <div class="alert alert-danger">
                        <ul class="p-0 m-0">
                            @foreach($errors->get('unit_of_measurement_id') as $error)
                                <li class="m-0 p-0"> {{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


                 <div class="input-group mb-3">
                    <span class="input-group-text" id="project">Проект к которому относится договор </span>
                    <select name="project_id"
                            class="form-control {{$errors->has('project_id')?'is-invalid':''}}"
                            aria-describedby="projects" id="projects" style="display: max-widh:300px;">
                        @foreach ($projects as $project)
                            <option
                                value="{{$project->id}}" {{($project->id == $agreement->project_id) ? 'selected' : ''}}>
                                {{$project->subject}}
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

                <div class="input-group mb-3">
                    <span class="input-group-text" id="cfsItem">Статья ОДДС </span>
                    <select name="cfs_item_id"
                            class="selectpicker {{$errors->has('cfs_item_id')?'is-invalid':''}}"
                            aria-describedby="cfsItems" data-live-search="true" id="cfsItems">
                            <option value=""> *Нет статьи*  </option>
                        @foreach ($cfsGroups as $cfsGrpoup)
                            <option value=""> {{strtoupper($cfsGrpoup->name)}} </option>
                            @foreach($cfsGrpoup->items as $item)
                                <option value={{$item->id}} {{($item->id==$agreement->cfs_item_id)?'selected':''}}> -- {{$item->name}}</option>
                            @endforeach
                        @endforeach
                    </select>
                </div>
                @if ($errors->has('cfs_item_id'))
                    <div class="alert alert-danger">
                        <ul class="p-0 m-0">
                            @foreach($errors->get('cfs_item_id') as $error)
                                <li class="m-0 p-0"> {{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="form-group">
                    <label for="description">Комментарий</label>
                    <textarea class="form-control {{$errors->has('description')?'is-invalid':''}}"
                              id="description"
                              rows="7" name="description">{{$agreement->description}}</textarea>
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
        <a class="btn btn-secondary" href="{{session('previous_url', route('admin.agreements'))}}">Отмена</a>

    </form>

@endsection

@section('scripts')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
{{-- <script src="/select2/dist/js/select2.min.js"></script> --}}
<script src="/select2/dist/js/i18n/ru.js"></script>

<script>
    $(document).ready(function() {
    $('#cfsItems').select2({
            placeholder: "Выберите Статью БДДС",
            maximumSelectionLength: 2,
            language: "ru"
        });
    $('#projects').select2({
            placeholder: "Выберите проект",
            maximumSelectionLength: 2,
            language: "ru"
        });
        
    });
    $('#counterparties').select2({
            placeholder: "Выберите контрагента",
            maximumSelectionLength: 2,
            language: "ru"
        });
        
    
    
</script>
@endsection
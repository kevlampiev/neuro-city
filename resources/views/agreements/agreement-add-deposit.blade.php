@extends('layouts.admin')

@section('title')
    Администратор|Добавить технику к договору
@endsection

@section('content')
    <h3> Добавить технику в залог по договору {{$agreement->agr_num}} от {{$agreement->date_open}}</h3>
    <form method="POST">
        @csrf
        <div class="row">
            <div class="col-md-11">

                <input type="hidden" name="agreement_id" value="{{$agreement->id}}">
                <div class="input-group mb-3">
                    <label for="vehicles">Единица техники</label>
                    <select name="vehicle_id" class="form-control" id="vehicles" data-live-search="true">
                        @foreach ($vehicles as $vehicle)
                            <option
                                value="{{$vehicle->id}}" {{($vehicle->id == $deposit->vehicle_id) ? 'selected' : ''}}>
                                {{$vehicle->name}} модель:{{$vehicle->model}} номер:{{$vehicle->bort_number}}
                                VIN:{{$vehicle->vin}}
                            </option>
                        @endforeach
                    </select>
                </div>
                @if ($errors->has('vehicle_id'))
                    <div class="alert alert-danger">
                        <ul class="p-0 m-0">
                            @foreach($errors->get('vehicle_id') as $error)
                                <li class="m-0 p-0"> {{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="input-group mb-3">
                    <span class="input-group-text" id="date_open">Срок залога</span>
                    <input type="date"
                           class="form-control {{$errors->has('date_open')?'is-invalid':''}}"
                           aria-describedby="date_open"
                           placeholder="Дата заключения" name="date_open"
                           value="{{$deposit->date_open}}">
                    <input type="date"
                           class="form-control {{$errors->has('date_close')?'is-invalid':''}}"
                           aria-describedby="date_close"
                           placeholder="Планируемая дата окончания" name="date_close"
                           value="{{$deposit->date_close}}">
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
                           value="{{$deposit->real_date_close}}">
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
                    <span class="input-group-text" id="description">Договор залога/комментарий</span>
                    <input type="text"
                           class="form-control {{$errors->has('description')?'is-invalid':''}}"
                           aria-describedby="description"
                           placeholder="Введите название договора/комментарий" name="description"
                           value="{{$deposit->description}}">
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
            Добавить
        </button>
        <a class="btn btn-secondary" href="{{route('admin.agreementSummary',['agreement'=>$agreement])}}">Отмена</a>


    </form>

@endsection

@section('scripts')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#vehicles').select2();
        })
    </script>
@endsection
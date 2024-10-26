@extends('layouts.big-form')

@section('title')
    Редактирование платежа
@endsection

@section('content')
    <h3> @if ($model->id)
            Изменение платежа
        @else
            Добавление платежа
        @endif
    </h3>
    <form method="POST" enctype="multipart/form-data" 
        action="{{$model->id ? route('payments.update', ['payment' => $model->id]) : route('payments.store')}}">
        @csrf
        @if ($model->id)
            @method('PUT')
        @endif
        <div class="row">
            <div class="col-md-12">
                <input type="hidden" name="id" value="{{$model->id}}">
                
                <div class="form-group">
                    <label for="inputDateOpen">Дата платежа</label>
                    <input type="date" class="form-control {{$errors->has('date_open') ? 'is-invalid' : ''}}" id="inputDateOpen"
                           name="date_open" value="{{$model->date_open}}">
                </div>
                
                {{-- Error handling for date_open --}}
                @include('partials.error', ['field' => 'date_open'])

                <div class="form-group">
                    <label for="bank">Банковский счет</label>
                    <select name="bank_account_id"
                            class="form-control select2 {{$errors->has('bank_account_id') ? 'is-invalid' : ''}}"
                            id="bank">
                        @foreach ($accounts as $account)
                            <option value="{{$account->id}}" {{($account->id == $model->bank_account_id) ? 'selected' : ''}}>
                                Владелец: {{$account->owner->name}} [ Банк: {{$account->bank->name}}, Р.сч: {{$account->account_number}} ]
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Error handling for bank_account_id --}}
                @include('partials.error', ['field' => 'bank_account_id'])

                <div class="form-group">
                    <label for="agreement">Номер договора</label>
                    <select name="agreement_id"
                            class="form-control select2 {{$errors->has('agreement_id') ? 'is-invalid' : ''}}"
                            id="agreement">
                        @foreach ($agreements as $agreement)
                            <option value="{{$agreement->id}}" {{($agreement->id == $model->agreement_id) ? 'selected' : ''}}>
                                {{$agreement->name}} № {{$agreement->agr_number}} от {{\Carbon\Carbon::parse($agreement->date_open)->format('d.m.Y')}}
                                между {{$agreement->buyer->name}} и {{$agreement->seller->name}}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Error handling for agreement_id --}}
                @include('partials.error', ['field' => 'agreement_id'])

                <div class="form-group">
                    <label for="inputAmount">Сумма</label>
                    <input type="number" class="form-control formatted-input {{$errors->has('amount') ? 'is-invalid' : ''}}" 
                           id="inputAmount" name="amount" value="{{$model->amount}}" step="0.01" oninput="formatNumber(this)">
                </div>

                {{-- Error handling for amount --}}
                @include('partials.error', ['field' => 'amount'])

                <div class="form-group">
                    <label for="inputVAT">В т.ч. НДС</label>
                    <input type="number" class="form-control formatted-input {{$errors->has('VAT') ? 'is-invalid' : ''}}" 
                           id="inputVAT" name="VAT" value="{{$model->VAT}}" step="0.01" oninput="formatNumber(this)">
                </div>

                {{-- Error handling for VAT --}}
                @include('partials.error', ['field' => 'VAT'])

                <div class="form-group">
                    <label for="project_id">Проект</label>
                    <select name="project_id"
                            class="form-control select2 {{$errors->has('project_id') ? 'is-invalid' : ''}}"
                            id="project_id">
                        <option value="" {{($model->project_id == null) ? 'selected' : ''}}>
                            *БЕЗ ПРОЕКТА*
                        </option>
                        @foreach ($projects as $project)
                            <option value="{{$project->id}}" {{($project->id == $model->project_id) ? 'selected' : ''}}>
                                {{$project->name}}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Error handling for project_id --}}
                @include('partials.error', ['field' => 'project_id'])

                <div class="form-group">
                    <label for="cfs_item_id">Статья ОДДС</label>
                    <select name="cfs_item_id"
                            class="form-control select2 {{$errors->has('cfs_item_id') ? 'is-invalid' : ''}}"
                            id="cfs_item_id">
                        @foreach ($cfsItems as $item)
                            <option value="{{$item->id}}" {{($item->id == $model->cfs_item_id) ? 'selected' : ''}}>
                                {{$item->name}}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Error handling for cfs_item_id --}}
                @include('partials.error', ['field' => 'cfs_item_id'])

                <div class="form-group">
                    <label for="description">Описание</label>
                    <textarea id="description"
                              class="form-control {{$errors->has('description') ? 'is-invalid' : ''}}"
                              placeholder="Введите описание"
                              name="description">{{ old('description', $model->description) }}</textarea>
                </div>

                {{-- Error handling for description --}}
                @include('partials.error', ['field' => 'description'])

                <div class="row mt-3">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            @if ($model->id)
                                Изменить
                            @else
                                Добавить
                            @endif
                        </button>
                        <a class="btn btn-secondary" href="{{ route('payments.index') }}">Отмена</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
    <!-- Подключаем Select2 -->
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <!-- CSS -->
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <!-- JS -->
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });

        // Функция для форматирования чисел в полях input
        function formatNumber(element) {
            element.value = parseFloat(element.value.replace(/\s/g, '') || 0).toLocaleString('ru-RU', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }
    </script>
@endsection

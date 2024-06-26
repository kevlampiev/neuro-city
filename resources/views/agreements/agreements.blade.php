@extends('layouts.admin')

@section('title')
    Администратор| Договоры
@endsection

@section('content')

    <div class="row">
        <h2>Заключенные договоры</h2>

    </div>

    @if ($filter!=='')
        <div class="alert alert-primary" role="alert">
            Установлен фильтр по номеру договора " <strong> {{$filter}} </strong> "
        </div>
    @endif

    <div class="row">
        @if(Gate::allows('e-agreement'))
        <div class="col-md-2">
            <a class="btn btn-outline-info" href="{{route('admin.addAgreement')}}">Новый договор</a>
        </div>
        @endif
        <div class="col-md-10">
            <form class="form-inline my-2 my-lg-0" method="GET">

                <span class="input-group-text" id="cfsItem">Статус </span>
                <select class="form-control" id="exampleFormControlSelect1" name="status" value="{{$agreementStatus}}">
                    <option value="all" {{($agreementStatus=='all'?'selected':'')}}>Действующие и закрытые</option>
                    <option value="current" {{($agreementStatus=='current'?'selected':'')}}>Только действующие</option>
                    <option value="closed" {{($agreementStatus=='closed'?'selected':'')}}>Только закрытые</option>
                </select>
                
               <span class="input-group-text" id="cfsItem">Статья ДДС</span> 
               <select class="form-control" id="CFSItemsSelect" name="cfs_item_id" value="{{$cfs_item_id}}">
                    <option value="all" {{($cfs_item_id=='all')?'selected':''}}>*Все статьи*</option>
                    <option value="none" {{($cfs_item_id=='none')?'selected':''}}>*нет*</option>
                    @foreach ($cfsItems as $item)
                        <option value="{{$item->id}}" {{($cfs_item_id==$item->id?'selected':'')}}>{{$item->group->name}} / {{$item->name}}</option>    
                    @endforeach
                </select>

                <span class="input-group-text" id="cfsItem">Текст для поиска </span>
                <input class="form-control mr-sm-2" type="search" placeholder="Поиск в договорах" aria-label="Search"
                       name="searchStr"
                       value="{{isset($filter)?$filter:''}}">
                <button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Поиск</button>
            </form>
        </div>

    </div>

    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Наименование</th>
                    <th scope="col">Компания</th>
                    <th scope="col">Контрагент</th>
                    <th scope="col">Тип договора</th>
                    <th scope="col">Номер договора</th>
                    <th scope="col">Дата договора</th>
                    <th scope="col">Дата завершения</th>
                    <th scope="col">Проект</th>
                    <th scope="col">Статья</th>
                    <th scope="col">Просроченная оплата на сегодня, руб</th>

                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                @forelse($agreements as $index=>$agreement)
                    <tr @if($agreement->real_date_close&&$agreement->real_date_close<=now()) class="text-light text-decoration-line-through"@endif>
                        <th scope="row">{{($index+1)}}</th>
                        <td>{{$agreement->name}}</td>
                        <td>{{$agreement->company->name}}</td>
                        <td>{{$agreement->counterparty->name}}</td>
                        <td>{{$agreement->agreementType->name}}</td>
                        <td>{{$agreement->agr_number}}</td>
                        <td>{{\Illuminate\Support\Carbon::parse($agreement->date_open)->format('d.m.Y')}}</td>
                        <td>{{\Illuminate\Support\Carbon::parse($agreement->real_date_close)->format('d.m.Y')}}</td>
                        <td>{{$agreement->project?$agreement->project->subject:'--'}}</td>
                        <td>{{$agreement->cfsItem?$agreement->cfsItem->name:'--'}}</td>
                        <td>
                            {{number_format(max($agreement->payments->where('payment_date',"<",now())->sum('amount')-$agreement->realPayments->where('payment_date',"<",now())->sum('amount'),0),2)}}
                        </td>

                        <td><a href="{{route('admin.agreementSummary',['agreement'=>$agreement])}}">
                                &#9776;Карточка </a></td>
                        <td>
                            @if(Gate::allows('e-agreement'))
                            <a href="{{route('admin.editAgreement',['agreement'=>$agreement])}}"> &#9998;Изменить </a>
                            @endif
                        </td>
                        <td>
                            @if(Gate::allows('e-agreement'))
                            <a href="{{route('admin.deleteAgreement',['agreement'=>$agreement])}}"
                               onclick="return confirm('Действительно удалить данные о договоре?')">
                                &#10008;Удалить </a>
                            @endif    
                        </td>
                    </tr>
                @empty
                    <td colspan="11">Нет записей</td>
                @endforelse
                </tbody>
            </table>
            {!! $agreements->appends(request()->input())->links() !!}
        </div>
    </div>
@endsection

@section('scripts')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="/select2/dist/js/i18n/ru.js"></script>

<script>
    $(document).ready(function() {
    $('#CFSItemsSelect').select2({
            placeholder: "Выберите Статью БДДС",
            maximumSelectionLength: 2,
            language: "ru"
        });
    });

    
</script>
@endsection
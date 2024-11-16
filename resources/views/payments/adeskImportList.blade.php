@extends('layouts.big-form')

@section('title')
    Импорт
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">
            <h2>Операции для импорта из ADesk</h2>
        </div>
    </div>

    <div class="row">

        <div class="col-md-12">
            <form class="form-inline my-2 my-lg-0" method="GET">
                <div class="d-flex align-items-center">
                    <div class="input-group me-2">
                        <span class="input-group-text" id="cfsItem">Текст для поиска</span>
                        <input class="form-control" type="search" placeholder="Поиск в договорах" aria-label="Search"
                            name="searchStr"
                            value="{{isset($filter)?$filter:''}}">
                    </div>
                    <button class="btn btn-outline-primary" type="submit">Поиск</button>
                </div>
            </form>
        </div>
    </div>



    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Дата</th>
                    <th scope="col">Банк</th>
                    <th scope="col">Компания</th>
                    <th scope="col">Контрагент</th>
                    <th scope="col">Проект</th>
                    <th scope="col">Основание</th>
                    <th scope="col">Статья</th>
                    <th scope="col">Сумма, руб</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                @forelse($adeskPayments as $index => $item)
                    <tr class="{{(!$item->ready_to_load)?'table-danger':''}}">
                        <th scope="row">{{$item->adesk_id}}</th>
                        <td> {{\Carbon\Carbon::parse($item->date_open)->format('d.m.Y')}}</td>
                        <td class="{{(!$item->bank_account_id)?'text-danger':''}}">
                            <p>
                               р/c {{($item->bankAccount->account_number)??''}} в {{($item->bankAccount->bank->name)??''}}
                            </p> 
                            <p class="text-secondary fst-italic">
                             {{$item->adesk_bank_name}}
                            </p>
                        </td>
                        <td> 
                            {{($item->bankAccount->owner->name)??''}}
                            <p class="text-secondary fst-italic">
                             {{$item->adesk_company_name}}
                            </p>
                        </td>
                        <td>
                            <p class="text-secondary fst-italic">
                                 {{$item->adesk_contractor_name}}
                            </p>
                            
                        </td>
                       
                       
                        <td>
                            <p> {{$item->project->name??'не определен'}} </p>
                        </td>

                        <td> {{$item->description}} </td>
                        
                        <td class="{{(!$item->cfs_item_id)?'text-danger':''}}">{{$item->adesk_cfs_category_name}}</td>

                        <td class="text-end">{{number_format($item->amount, 2, '.', ',')}}</td>
                        
                        <td>
                            @if(Gate::allows('e-payments'))
                                <a href="{{route('import.adesk.payments.edit', ['adesk_id' => $item->adesk_id])}}"> <i class="bi bi-pencil-square"></i> Изменить </a>
                            @endif    
                        </td>
                        
                        <td>
                            @if(Gate::allows('e-payments'))
                                <form action="#" method="POST" onsubmit="return confirm('Действительно удалить данные о платеже?')">
                                    @csrf
                                    @method('DELETE') <!-- Указываем метод DELETE -->
                                    <button type="submit" class="btn btn-outline-danger" style="border: none;">&#10008;Удалить</button>
                                </form>
                            @endif
                        </td>

                    </tr>
                @empty
                    <td colspan="10">Нет записей</td>
                @endforelse
                </tbody>
            </table>
            {!! $adeskPayments->appends(request()->input())->links() !!}
        </div>
    </div>
@endsection

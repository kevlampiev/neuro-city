@extends('layouts.big-form')

@section('title')
    Правила Adesk
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">
            <h2>Перечень правил для рашифровки Adesk</h2>
        </div>
    </div>

    <div class="row">

        <div class="col-md-12">
            <form class="form-inline my-2 my-lg-0" method="GET">
                <div class="d-flex align-items-center">
                    <div class="input-group me-2">
                        <span class="input-group-text" id="cfsItem">Текст для поиска</span>
                        <input class="form-control" type="search" placeholder="Поиск в правилах" aria-label="Search"
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
                    <th scope="col">Наименование</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                @forelse($adeskRules as $index => $item)
                    <tr class="{{(!$item->ready_to_load)?'table-danger':''}}">
                        <th scope="row">{{$index + 1 }}</th>
                        <td> {{$item->name}}</td>

                        <td>
                            <a href="{{route('import.adesk.rules.edit', ['adeskRule' => $item])}}"> 
                                <i class="bi bi-pencil-square"></i>Изменить
                            </a>
                        </td>

                        <td>
                            @if(Gate::allows('e-payments'))
                                <form action="#" method="POST" onsubmit="return confirm('Действительно удалить правило?')">
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
            {!! $adeskRules->appends(request()->input())->links() !!}
        </div>
    </div>
@endsection

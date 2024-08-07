@extends('layouts.big-form')

@section('title')
    Администратор| Справочники
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2>Виды договоров</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <a class="btn btn-outline-info" href="{{route('addAgrType')}}">Новый тип</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Наименование</th>
                    <th scope="col">Покупка/продажа</th>
                    <th scope="col">Сфера деятельности</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                @forelse($agrTypes as $index => $type)
                    <tr @if($type->system) class="table-warning" @endif>
                        <th scope="row">{{$index + 1}}</th>
                        <td>{{$type->name}}</td>
                        <td>{{($type->direction=='sell')?"Реализация":"Закупка"}}</td>
                        <td>{{($type->segment=='operations')?"Операционная деятельность":(($type->segment=='finance')?"Финансы":"Инвестиции")}}</td>
                        <td>
                            @if(!$type->system)
                                <a href="{{route('editAgrType',['agrType'=>$type])}}"> &#9998;Изменить </a>
                            @endif    
                        </td>
                        @if (($type->agreements_count==0)&&(!$type->system))
                            <td><a href="{{route('deleteAgrType',['agrType'=>$type])}}"
                                   onclick="return confirm('Действительно удалить данные о типе договора?')">
                                    &#10008;Удалить </a></td>
                        @else
                            <td> системный тип </td>
                        @endif
                    </tr>
                @empty
                    <td colspan="4">Нет записей</td>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

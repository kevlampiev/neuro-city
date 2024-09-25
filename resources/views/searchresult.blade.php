@extends('layouts.big-form')

@section('title')
    Результаты поиска
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">
            <h2>Результаты поиска по строке <i>{{$searchStr}}</i></h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            
            @forelse($searchResult as $index=>$item)
            <div class="card m-2">
                <div class="card-body">
                    <h5 class="card-title">{{$index}} {{$item->obj_type}}</h5>
                    <p class="card-text">{{$item->obj_text}}</p>
                    <a href="#" class="card-link">Another link</a>
                </div>
            </div>    
            @empty
                <p> Нет данных для отображения </p>
            @endforelse    
            
        </div>
    </div>
@endsection

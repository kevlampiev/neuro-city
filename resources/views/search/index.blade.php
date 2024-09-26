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
                @include('search.components.'.$item->obj_type)
            </div>    
            @empty
                <p> Нет данных для отображения </p>
            @endforelse    
            {{ $searchResult->appends(['searchStr' => $searchStr])->links() }}    
        </div>

    </div>
@endsection

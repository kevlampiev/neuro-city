@extends('layouts.big-form')

@section('title')
    Администратор| Группы статей CFS
@endsection

@section('content')

    <div class="row">
        <h2>Группы статей CFS</h2>

    </div>

    <div class="row  m-3">
        <div class="col-md-12">
            <a class="btn btn-outline-info" href="{{route('addCfsGroup')}}">Новая группа</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <h4> Операционная деятельность </h4>
            <div class = "ps-5"> 
                @include('cfss.cfs-groups.cfs-group-record',['groups'=>$CFOGroups])
            </div>    

            <h4> Инвестиционная деятельность </h4>
            <div class = "ps-5"> 
                @include('cfss.cfs-groups.cfs-group-record',['groups'=>$CFIGroups])
            </div>    
                      
            <h4> Финансовая деятельность </h4>
            <div class = "ps-5"> 
                @include('cfss.cfs-groups.cfs-group-record',['groups'=>$CFFGroups])
            </div>              
        </div>
    </div>
@endsection


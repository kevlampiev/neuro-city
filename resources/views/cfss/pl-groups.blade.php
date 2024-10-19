@extends('layouts.big-form')

@section('title')
    Группы статей PL
@endsection

@section('content')

    <div class="row">
        <h2>Группы статей PL</h2>

    </div>

    <div class="row">
        <div class="col-md-12">
            <a class="btn btn-outline-info" href="{{route('addPlGroup')}}">Новая группа</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <h3> Выручка </h3>
            <div class = "pr-5"> 
                @include('cfss.pl-groups.pl-group-record',['groups'=>$sales])
            </div>    
            
            <h4> Себестоимость </h4>
            <div class = "pr-5"> 
                @include('cfss.pl-groups.pl-group-record',['groups'=>$cogs])
            </div>    

            <hr>
            <h3> Валовая прибыль </h3>

            <h4> Косвенные расходы </h4>
            <div class = "pr-5"> 
                @include('cfss.pl-groups.pl-group-record',['groups'=>$indirect_costs])
            </div>    

            <hr>
            <h3> EBITDA </h3>


            <h4> Амортизация </h4>
            <div class = "pr-5"> 
                @include('cfss.pl-groups.pl-group-record',['groups'=>$DA])
            </div>    

            <hr>
            <h3> EBIT </h3>

            <h4> Проценты </h4>
            <div class = "pr-5"> 
                @include('cfss.pl-groups.pl-group-record',['groups'=>$interests])
            </div>    

            <hr>
            <h3> EBT </h3>

            <h4> Налог на прибыль </h4>
            <div class = "pr-5"> 
                @include('cfss.pl-groups.pl-group-record',['groups'=>$tax])
            </div>    

            <hr>
            <h3> NP </h3>

        </div>
    </div>
@endsection


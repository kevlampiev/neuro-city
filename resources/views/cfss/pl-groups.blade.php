@extends('layouts.big-form')

@section('title')
    Группы статей PL
@endsection

@section('content')

    <div class="row">
        <h2>Группы статей PL</h2>

    </div>

    <div class="row mb-3">
        <div class="col-md-12">
            <a class="btn btn-outline-info" href="{{route('addPlGroup')}}">Новая группа</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <h4> Выручка </h4>
            <div class = "m-3"> 
                @include('cfss.pl-groups.pl-group-record',['groups'=>$sales])
            </div>    
            
            <h5> Себестоимость </h5>
            <div class = "m-3"> 
                @include('cfss.pl-groups.pl-group-record',['groups'=>$cogs])
            </div>    

            <hr class="hr hr-blurry" />
            <h4> Валовая прибыль </h4>

            <h5> Косвенные расходы </h5>
            <div class = "m-3"> 
                @include('cfss.pl-groups.pl-group-record',['groups'=>$indirect_costs])
            </div>    

            <hr class="hr hr-blurry" />
            <h4> EBITDA </h4>


            <h5> Амортизация </h5>
            <div class = "m-3"> 
                @include('cfss.pl-groups.pl-group-record',['groups'=>$DA])
            </div>    

            <hr class="hr hr-blurry" />
            <h4> EBIT </h4>

            <h5> Проценты </h5>
            <div class = "m-3"> 
                @include('cfss.pl-groups.pl-group-record',['groups'=>$interests])
            </div>    

            <hr class="hr hr-blurry" />
            <h4> EBT </h4>

            <h5> Налог на прибыль </h5>
            <div class = "m-3"> 
                @include('cfss.pl-groups.pl-group-record',['groups'=>$tax])
            </div>    

            <hr class="hr hr-blurry" />
            <h4> NP </h4>

        </div>
    </div>
@endsection


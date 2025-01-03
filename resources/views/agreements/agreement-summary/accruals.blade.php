<div class="row">
    @if(Gate::allows('s-accruals'))
        <div class="col-md-6 p-2">
            <h4>Плановые начисления</h4>
            @include('agreements.agreement-summary.accruals.plan')
        </div>
        <div class="col-md-6 p-2">
            <h4>Фактические начисления</h4>
            @include('agreements.agreement-summary.accruals.fact')
        </div>
    @else 
        <p> у Вас нет доступа к такой информации </p>   
    @endif
</div>

<div class="row">
    @if(Gate::allows('s-payments'))
        <div class="col-md-6 p-2">
            <h4>Плановые платежи</h4>
            @include('agreements.agreement-summary.payments.plan')
        </div>
        <div class="col-md-6 p-2">
            <h4>Фактические платежи</h4>
            @include('agreements.agreement-summary.payments.fact')
        </div>
    @else 
        <p> у Вас нет доступа к такой информации </p>   
    @endif
</div>


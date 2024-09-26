<div class="card-body">
    <h5 class="card-title"> Контрагент </h5>
    <p class="card-text">{{$item->obj_text}}</p>
    <a href="{{route('counterpartySummary', ['counterparty'=>$item->id])}}" class="card-link">Карточка контрагента</a>
</div>
<div class="card-body">
    <h5 class="card-title">Сотрудник компании</h5>
    <p class="card-text">{{$item->obj_text}}</p>
    <a href="{{route('counterpartySummary', ['counterparty'=>$item->id, 'page'=>'notes'])}}" class="card-link">Карточка компании</a>
</div>
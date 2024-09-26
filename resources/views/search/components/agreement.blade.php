<div class="card-body">
    <h5 class="card-title">{{$index}} Договор</h5>
    <p class="card-text">{{$item->obj_text}}</p>
    <a href="{{route('agreementSummary', ['agreement'=>$item->id])}}" class="card-link">Карточка договора</a>
</div>
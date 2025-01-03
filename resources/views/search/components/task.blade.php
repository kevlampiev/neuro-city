<div class="card-body">
    <h5 class="card-title">Задача</h5>
    <p class="card-text">{{$item->obj_text}}</p>
    <a href="{{route('taskCard', ['task'=>$item->id])}}" class="card-link">Карточка задачи</a>
</div>
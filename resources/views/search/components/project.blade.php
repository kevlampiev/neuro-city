<div class="card-body">
    <h5 class="card-title">Проект</h5>
    <p class="card-text">{{$item->obj_text}}</p>
    <a href="{{route('projects.summary', ['project'=>$item->id])}}" class="card-link">Карточка проекта</a>
</div>
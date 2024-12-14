<nav class="nav mb-3">
    @if(Auth::user()->id == $task->performer->id)
        <a class="btn btn-outline-info" aria-current="page"
            href="{{route('addTask', ['parentTask'=>$task])}}">Новая дочерняя задача</a>
    @endif   
</nav>

<nav class="nav">
    <a class="btn btn-outline-info m-2" aria-current="page"
       href="{{route('addTaskDocument', ['task'=>$task])}}">Добавить новый документ</a>
    <a class="btn btn-outline-info m-2"
        href="{{route('addTaskManyDocuments',['task' => $task])}}">
        <i class="bi bi-folder-plus"></i>
        Добавить несколько 
    </a>   
</nav>

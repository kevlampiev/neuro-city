<ul @if(isset($listId)) id="{{$listId}}" @endif>
    @foreach($tasks as $task)
      <li class="task-item"> @include('tasks.task-record') 
        @if(count($task->subTasks)>0)
          <ul> @include('tasks.tasks-tree', ['tasks' => $task->subTasks]) </ul>
        @endif
      </li>
    @endforeach
</ul>

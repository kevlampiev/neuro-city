@foreach($subtasks as $task)
    @if(count($task->subTasks)>0)
        <details>
            <summary class="has-child">
                @include('tasks.task-record')
            </summary>
            <div class="ml-5 mb-1">
                @include('tasks.subtasks', ['subtasks' => $task->subTasks])
            </div>
        </details>
    @else
        <div class="no-childs mb-3">
            @include('tasks.task-record')
        </div>
    @endif

@endforeach

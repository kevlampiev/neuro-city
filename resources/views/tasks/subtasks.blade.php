@foreach($subtasks as $task)
    @if(count($task->subTasks)>0)
        <details>
            <summary class="has-child">
                @include('tasks.task-record')
            </summary>
            <div class="ms-4 mb-1">
                @include('tasks.subtasks', ['subtasks' => $task->subTasks])
            </div>
        </details>
    @else
        <div class="no-childs ms-4">
            @include('tasks.task-record')
        </div>
    @endif

@endforeach

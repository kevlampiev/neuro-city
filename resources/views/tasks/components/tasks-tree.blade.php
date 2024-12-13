
<div class="dd" id="nestable">
    <ul class="dd-list">
        @foreach($tasks as $task)
            <li class="dd-item" data-id="{{ $task->id }}">
                <div class="dd-handle">
                    @include('tasks.task-record', ['task' => $task])
                </div>
                
                @if($task->subTasks->isNotEmpty())
                    <ul class="dd-list">
                        @foreach($task->subTasks as $subtask)
                            <li class="dd-item" data-id="{{ $subtask->id }}">
                                <div class="dd-handle">
                                    @include('tasks.task-record', ['task' => $subtask])
                                </div>
                                
                                @if($subtask->subTasks->isNotEmpty())
                                    <ul class="dd-list">
                                        @foreach($subtask->subTasks as $subSubtask)
                                            <li class="dd-item" data-id="{{ $subSubtask->id }}">
                                                <div class="dd-handle">
                                                    @include('tasks.task-record', ['task' => $subSubtask])
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </li>
        @endforeach
    </ul>
</div>
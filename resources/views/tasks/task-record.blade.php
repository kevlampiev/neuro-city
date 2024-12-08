<a href="{{route('taskCard', ['task' => $task])}}"
   class="pl-3">
    @php
        $taskStr = 'задача #'.$task->id.' -- '.$task->subject;
    @endphp

    @if(($task->due_date<\Carbon\Carbon::now())&&(!$task->terminate_date))
        <i class="fa fa-fire text-danger" aria-hidden="true"></i>
    @elseif((\Carbon\Carbon::parse($task->due_date)->diffInDays(now())<3)&&(!$task->terminate_date))
        <i class="fa fa-fire text-warning" aria-hidden="true"></i>
    @endif

    @if($task->importance == 'high')
        <span class="text-danger font-weight-bold"> {{$taskStr}}</span>
    @elseif($task->importance == 'low')
        <span class="text-secondary"> {{$taskStr}}</span>
    @else
        <span class="text-black"> {{$taskStr}}</span>
    @endif

    <span class="text-secondary small font-italic pl-3"
          @if(count($task->subTasks)==0)
              class="ml-3"
                      @endif
                > {{(!$task->parent_task_id)?"Руководитель":"Исп"}}:{{$task->performer->name}}
                        &nbsp; Срок:{{\Carbon\Carbon::parse($task->due_date)->format('d.m.Y')}}

                </span>

</a>

    @php
        $taskStr = '#' . $task->id . ' ' . $task->subject;
        $isOverdue = $task->due_date < now() && !$task->terminate_date;
        $isUrgent = !$isOverdue && now()->diffInDays($task->due_date) < 3 && !$task->terminate_date;
        $isClosed = !is_null($task->terminate_status)
    @endphp

    {{-- Иконка статуса задачи --}}
    @if($isOverdue)
        <i class="bi bi-fire text-danger"></i>
    @elseif($isUrgent)
        <i class="bi bi-fire text-warning"></i>
    @elseif($task->terminate_status=="complete")
        <i class="bi bi-check-circle"></i>
    @elseif($task->terminate_status=="cancel")
        <i class="bi bi-x-circle"></i>
    @endif

    
    <span 
        @class([
            'text-danger fw-bold' => $task->importance === 'high',
            'text-secondary' => $task->importance === 'low',
            'text-black' => $task->importance !== 'high' && $task->importance !== 'low',
            'text-black-50 text-decoration-line-through' => $isClosed,
        ])
    >
        {{ $taskStr }}
    </span>

    {{-- Дополнительная информация --}}
    <span class="text-secondary small font-italic p-1 ">
            <em>
                @if($task->user_id != $task->task_performer_id)
                    {{$task->user->name }} 
                    {{$task->performer->name }} &nbsp;
                @else
                    {{$task->performer->name }} 
                @endif    
            &nbsp; Срок: {{ \Carbon\Carbon::parse($task->due_date)->format('d.m.Y') }} </em>
    </span>
    <a href="{{ route('taskCard', ['task' => $task]) }}" class="task-description"> &nbsp;&nbsp; &#9776; Карточка </a>


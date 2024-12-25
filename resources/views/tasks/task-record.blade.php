    @php
        $taskStr = '#' . $task->id . ' ' . $task->subject;
        $isOverdue = $task->due_date < now() && !$task->terminate_date;
        $isUrgent = !$isOverdue && now()->diffInDays($task->due_date) < 3 && !$task->terminate_date;
    @endphp

    {{-- Иконка статуса задачи --}}
    @if($isOverdue)
        <i class="bi bi-fire text-danger"></i>
    @elseif($isUrgent)
        <i class="bi bi-fire text-warning"></i>
    @endif

    
    <span 
        @class([
            'text-danger fw-bold' => $task->importance === 'high',
            'text-secondary' => $task->importance === 'low',
            'text-black' => $task->importance !== 'high' && $task->importance !== 'low',
        ])
    >
        {{ $taskStr }}
    </span>

    {{-- Дополнительная информация --}}
    <span class="text-secondary small font-italic p-1 ">
        <em>
            Исп: {{ $task->performer->name }}
            &nbsp; Срок: {{ \Carbon\Carbon::parse($task->due_date)->format('d.m.Y') }}
        </em>
    </span>
    <a href="{{ route('taskCard', ['task' => $task]) }}" class="task-description"> &nbsp;&nbsp; &#9776; Карточка </a>


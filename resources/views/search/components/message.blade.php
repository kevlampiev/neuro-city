@php
    // Преобразуем $item в объект модели Message
    $message = \App\Models\Message::find($item->id);
@endphp

@if($message)
    <div class="card-body">
        <h5 class="card-title">Комментарий по задаче</h5>
        <p class="card-text">{!! $item->obj_text !!}</p>
        <a href="{{ route('taskCard', ['task' => $message->root_task, 'page'=>'messages']) }}" class="card-link">Карточка задачи</a>
    </div>
@else
    <div class="card-body">
        <h5 class="card-title">Комментарий не найден</h5>
    </div>
@endif
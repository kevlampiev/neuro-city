@foreach($replies as $message)
    <div class="card mb-2 border-light shadow-sm">
        <div class="card-body">
            @include('tasks.messages.message-record')
            @if(count($message->replies) > 0)
                <div class="ms-4 border-start border-2 ps-3 mt-3">
                    @include('tasks.messages.replies', ['replies' => $message->replies])
                </div>
            @endif
        </div>
    </div>
@endforeach

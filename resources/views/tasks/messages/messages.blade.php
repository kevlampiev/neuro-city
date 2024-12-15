
<div class="row">
    <div class="col-md-12">
        @foreach($messages->where('reply_to_message_id', '=', null)->sortByDesc('created_at') as $message)
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    @include('tasks.messages.message-record')
                    @if(count(collect($message->replies)) > 0)
                        <div class="ms-4 border-start border-2 ps-3 mt-3">
                            @include('tasks.messages.replies', ['replies' => $message->replies])
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>

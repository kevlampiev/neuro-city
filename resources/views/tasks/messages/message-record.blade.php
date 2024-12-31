<div>
    <div class="d-flex justify-content-between align-items-start">
        <div>
            <span class="text-primary fw-bold">
                {{$message->user->name}}
            </span>
            <small class="text-muted">
                {{\Carbon\Carbon::parse($message->created_at)->format('d.m.Y H:i')}}
            </small>
        </div>
    </div>
    <div class="mt-2">
        <p class="mb-0 text-body">{!! $message->description !!}</p>
    </div>
    @foreach($message->documents as $document)
        <a href="{{route('documentPreview',['document'=>$document])}}" target="_blank" class="btn btn-light">
            <img src="{{asset(strtolower(File::extension($document->file_name)).'.png')}}" style="width: 25px;">
            {{$document->description}}
        </a>    
    @endforeach
    <div class="mt-3 d-flex justify-content-end gap-2">
        <a href="{{route('messageReply', ['message' => $message])}}" class="btn btn-sm btn-outline-primary">
            <i class="fa fa-reply" aria-hidden="true"></i> Ответить
        </a>
        @if($message->user->id == Auth::user()->id)
                {{-- <a href="{{route('messageEdit', ['message' => $message])}}" class="btn btn-sm btn-outline-warning">
                    <i class="bi bi-pencil"></i> Изменить
                </a> --}}
            @if(count($message->replies) == 0)
                <a href="{{route('messageDelete', ['message' => $message])}}" 
                   onclick="return confirm('Вы действительно хотите удалить это сообщение?')" 
                   class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-trash"></i> Удалить
                </a>
            @endif
        @endif
    </div>
</div>

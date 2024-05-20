<div class="row m-1">
    <div class="col-md-12">
        @if(Gate::allows('e-counterparty'))
            <a class="btn btn-outline-info" href="{{route('addCounterpartyNote', ['counterparty'=>$counterparty])}}">Добавить
                заметку</a>
        @endif
        <div class="notes-container">

            @forelse($counterparty->notes as $index=>$note)
                <div class="card mb-2 mt-1">
                    <div class="card-header d-flex justify-content-between">
                        <div class="p-0">
                            <strong> {{$note->user->name}} </strong> {{$note->created_at}} 
                        </div>
                        @if(($note->user_id === Auth::user()->id)||(Auth::user()->is_superuser))
                        <div class="p-0">
                            <a href="{{route('editCounterpartyNote', ['counterpartyNote'=>$note])}}" class="btn btn-outline-info btn-sm">
                                &#9998;Изменить </a>
                            <a href="{{route('deleteCounterpartyNote', ['counterpartyNote' => $note])}}" class="btn btn-outline-info btn-sm"
                               onclick="return confirm('Действительно удалить заметку?')"> &#10008;Удалить </a>
                        </div>
                    @endif
                    </div>
                    <div class="card-body">
                        <p>{{$note->note_body}}</p>
                    </div>
                    
                </div>
            @empty
                <p class="font-italic text-secondary">Нет заметок по контрагенту</p>
            @endforelse

        </div>

    </div>
</div>

<div class="row m-1">
    <div class="col-md-12">
        @if(Gate::allows('e-counterparty'))
            <a class="btn btn-outline-info" href="{{route('addCounterpartyNote', ['counterparty'=>$counterparty])}}">Добавить
                заметку</a>
        @endif
        <div class="notes-container">

            @forelse($counterparty->notes as $index=>$note)
                <div class="card mb-3">
                    <div class="card-header">
                        <strong> {{$note->user->name}} </strong> {{$note->created_at}}
                    </div>
                    <div class="card-body">
                        <p>{{$note->note_body}}</p>
                    </div>
                    @if(($note->user_id === Auth::user()->id)||(Auth::user()->is_superuser))
                        <div class="card-footer text-muted">
                            <a href="{{route('editCounterpartyNote', ['counterpartyNote'=>$note])}}" class="mr-5">
                                &#9998;Изменить </a>
                            <a href="{{route('deleteCounterpartyNote', ['counterpartyNote' => $note])}}"
                               onclick="return confirm('Действительно удалить заметку?')"> &#10008;Удалить </a>
                        </div>
                    @endif
                </div>
            @empty
                <p class="font-italic text-secondary">Нет заметок по контрагенту</p>
            @endforelse

        </div>

    </div>
</div>

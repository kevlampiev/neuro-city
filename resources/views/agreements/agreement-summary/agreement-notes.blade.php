<div class="row m-1">
    <div class="col-md-12">
        @if(Gate::allows('s-agreements'))
            <a class="btn btn-outline-info mb-2" href="{{route('addAgreementNote', ['agreement'=>$agreement])}}">Добавить
                заметку</a>
        @endif    

        <div class="notes-container row">

            @forelse($agreement->notes as $index=>$note)
                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="card-header position-relative">
                            <strong> {{$note->user->name}} </strong> {{$note->created_at}}
                            @if(($note->user_id === Auth::user()->id)||(Auth::user()->role=="admin"))
                                <div class="note-actions position-absolute" style="top: 5px; right: 10px;">
                                    <a href="{{route('editAgreementNote', ['agreement'=>$agreement, 'agreementNote'=>$note])}}" class="mr-3">
                                        &#9998; </a>
                                    <a href="{{route('deleteAgreementNote', ['agreement'=>$agreement, 'agreementNote' => $note])}}"
                                       onclick="return confirm('Действительно удалить заметку?')"> &#10008; </a>
                                </div>
                            @endif
                        </div>
                        <div class="card-body">
                            <p>{{$note->description}}</p>
                        </div>
                    </div>
                </div>
            @empty
                <p class="font-italic text-secondary">Нет заметок по договору</p>
            @endforelse

        </div>

    </div>
</div>

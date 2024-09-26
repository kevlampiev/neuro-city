<div class="card-body">
    <h5 class="card-title">{{$index}} Документ</h5>
    <p class="card-text">{{$item->obj_text}}</p>
    @php
        $document = app\Model\Document::findOrFail($item->id);
    @endphp
    @foreach($document->agreements as $agreement)
        <a href="{{route('agreementSummary', ['agreement'=>$agreement])}}" class="card-link">Догвор {{$agreemens->agr_number}} от {{$agreements->date_open}}</a>
    @endforeach    
</div>
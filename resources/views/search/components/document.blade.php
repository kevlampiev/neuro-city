<div class="card-body">
    <h5 class="card-title">{{$index}} Документ</h5>
    <p class="card-text">{{$item->obj_text}}</p>
    @php
        $document = App\Models\Document::findOrFail($item->id);
    @endphp
    @foreach($document->agreements as $agreement)
        <a href="{{route('agreementSummary', ['agreement'=>$agreement])}}" class="card-link">Догвор {{$agreement->agr_number}} от {{$agreement->date_open}}</a>
    @endforeach    
</div>
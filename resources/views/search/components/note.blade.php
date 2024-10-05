<div class="card-body">
    <h5 class="card-title">Заметка</h5>
    <p class="card-text">{{$item->obj_text}}</p>
    @php
        $note = App\Models\Note::findOrFail($item->id);
    @endphp
    @if(Gate::allows('s-projects'))
        @foreach($note->projects as $project)
            <a href="{{route('projects.summary', ['project'=>$project])}}" class="card-link">Проект {{$project->name}}</a>
        @endforeach    
    @endif    

    @if(Gate::allows('s-agreements'))
        @foreach($note->agreements as $agreement)
            <a href="{{route('agreementSummary', ['agreement'=>$agreement])}}" class="card-link"> {{$agreement->name}} {{$agreement->agr_number}} от 
            {{\Illuminate\Support\Carbon::parse($agreement->date_open)->format('d.m.Y')}}</a>
        @endforeach    
    @endif    
    {{-- <a href="{{route('counterpartySummary', ['counterparty'=>$item->id, 'page'=>'notes'])}}" class="card-link">Карточка компании</a> --}}
</div>
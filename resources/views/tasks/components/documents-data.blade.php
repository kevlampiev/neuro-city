<div class="row">

    @forelse($task->documents as $index=>$document)
        <div class="col-sm-6 col-md-4 col-lg-3 d-flex">
            <div class="card m-2 flex-fill" style="min-width: 18rem; position: relative;">
                <div class="card-body">
                    <a
                        href="{{route('documentPreview', ['document'=>$document] ) }}"
                        class="text-dark"
                        target="_blank">
                        <img src="{{asset(File::extension($document->file_name).'.png')}}" style="width: 25px;">
                        <p class="card-text clr-gray mb-2 p-2">{{$document->description}}</p>
                    </a>
                    
                    
                    <!-- Кнопка в верхнем правом углу -->
                    @if(Auth::id()==$task->user_id)
                    <a href="{{route('detachTaskDocument', ['task' =>$task, 'document'=>$document])}}"
                       class="btn btn-outline-secondary"
                       style="position: absolute; top: 5px; right: 5px;"
                       onclick="return confirm('Действительно открепить документ?')">
                        &#10008;
                    </a>
                    @endif
                </div>
            </div>
        </div>

    @empty
        <p>Нет документов для отображения</p>
    @endforelse
</div>


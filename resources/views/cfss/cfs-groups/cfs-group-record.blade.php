 <div class="pl-5">

    
    @forelse($groups as $index=>$cfsGroup)
        <div class="row border-bottom">
            <div class="col-md-8">{{($cfsGroup->direction=="inflow")?'++':'--'}} <strong>{{$cfsGroup->name}}</strong></div>                    
            
            <div class="col-md-2"><a href="{{route('addCfsItem', ['cfsGroup' => $cfsGroup])}}">
                    &#9776;Добавить статью </a></div>


            <div class="col-md-1"><a href="{{route('editCfsGroup', ['cfsGroup' => $cfsGroup])}}">
                    &#9998;Изменить </a></div>
            
            <div class="col-md-1"><a href="{{route('deleteCfsGroup', ['cfsGroup' => $cfsGroup])}}"
                    onclick="return confirm('Действительно удалить данные о группе?')">
                    &#10008;Удалить </a>
            </div>
        </div>
        @forelse($cfsGroup->cfsItems as $index=>$cfsItem)
            <div class="row border-bottom">
                <div class="col-md-10 ps-5">{{$index+1}}&nbsp;&nbsp;{{$cfsItem->name}}</div>
                <div class="col-md-1">
                    <a href="{{route('editCfsItem', ['cfsItem' => $cfsItem])}}">
                    &#9998;Изменить </a>
                </div>
                <div class="col-md-1">
                    <a href="{{route('deleteCfsItem', ['cfsItem' => $cfsItem])}}"
                    onclick="return confirm('Действительно удалить данные о статье?')">
                    &#10008;Удалить </a>
                </div>
            </div>
        @empty
            <div class="row"> нет статей в группе </div>
        @endforelse    
    @empty
        <div class="row">Нет групп в данном разделе</div>
    @endforelse
    
</div>
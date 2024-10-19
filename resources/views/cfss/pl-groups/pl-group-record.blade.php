 <div class="pl-5">

    
    @forelse($groups as $index=>$plGroup)
        <div class="row border-bottom">
            <div class="col-md-9">{{($plGroup->direction=="inflow")?'++':'--'}} <strong>{{$plGroup->name}}</strong></div>                    
            
            <div class="col-md-1"><a href="{{route('addPlItem', ['plGroup' => $plGroup])}}">
                    &#9776;Добавить статью </a></div>


            <div class="col-md-1"><a href="{{route('editPlGroup', ['plGroup' => $plGroup])}}">
                    &#9998;Изменить </a></div>
            
            <div class="col-md-1"><a href="{{route('deletePlGroup', ['plGroup' => $plGroup])}}"
                    onclick="return confirm('Действительно удалить данные о группе?')">
                    &#10008;Удалить </a>
            </div>
        </div>
        @forelse($plGroup->plItems as $index=>$plItem)
            <div class="row border-bottom">
                <div class="col-md-10 pl-5">{{$index+1}}&nbsp;&nbsp;{{$plItem->name}}</div>
                <div class="col-md-1">
                    <a href="{{route('editPlItem', ['plItem' => $plItem])}}">
                    &#9998;Изменить </a>
                </div>
                <div class="col-md-1">
                    <a href="{{route('deletePlItem', ['plItem' => $plItem])}}"
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
<table>
    <tr>
        <td class="text-right text-black-50">Наименование</td>
        <td class="text-left p-2">{{$model->name}}</td>
    </tr>
    <tr>
        <td class="text-right text-black-50">Код ADesk</td>
        <td class="text-left p-2"> {{$model->adesk_id}}</td>
    </tr>
    
    <tr>
        <td class="text-right text-black-50">Дата начала</td>
        <td class="text-left p-2"> {{$model->established_date}}</td>
    </tr>

    <tr>
        <td class="text-right text-black-50">Описание</td>
        <td class="text-left p-2"> {{$model->description}}</td>
    </tr>

    <tr>
        <td class="text-right text-black-50"></td>
        <td>
            @if(Auth::user()->is_superuser)
                <a href="{{route('projects.edit',['id'=>$model->id])}}"
                   class="btn btn-outline-secondary" role="button" aria-pressed="true">Отредактировать</a>
            @endif
        </td>
    </tr>


</table>

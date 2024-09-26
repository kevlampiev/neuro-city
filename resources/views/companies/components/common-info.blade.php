<table>
    <tr>
        <td class="text-right text-black-50">Наименование</td>
        <td class="text-left p-2">{{$company->name}}</td>
    </tr>
    <tr>
        <td class="text-right text-black-50">ИНН</td>
        <td class="text-left p-2"> {{$company->inn}}</td>
    </tr>
    <tr>
        <td class="text-right text-black-50">ОГРН</td>
        <td class="text-left p-2"> {{$company->ogrn}}</td>
    </tr>
    <tr>
        <td class="text-right text-black-50">Дата регистрации</td>
        <td class="text-left p-2"> {{$company->established_date}}</td>
    </tr>

    <tr>
        <td class="text-right text-black-50">Идентификатор ADesk </td>
        <td class="text-left p-2"> {{$company->adesk_id}}</td>
    </tr>
    
    <tr>
        <td class="text-right text-black-50"></td>
        <td>
            @if(Auth::user()->is_superuser)
                <a href="{{route('editCompany',['company'=>$company])}}"
                   class="btn btn-outline-secondary" role="button" aria-pressed="true">Отредактировать</a>
            @endif
        </td>
    </tr>


</table>

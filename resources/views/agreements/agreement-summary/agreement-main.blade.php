<table>
    <tr>
        <td class="text-right text-black-50">Наименование</td>
        <td class="text-left p-2">{{$agreement->name}}</td>
    </tr>
    <tr>
        <td class="text-right text-black-50">Номер и дата</td>
        <td class="text-left p-2">№ {{$agreement->agr_number}}
            от {{\Carbon\Carbon::parse($agreement->date_open)->format('d.m.Y')}}</td>
    </tr>

    <tr>
        <td class="text-right text-black-50">Тип договора</td>
        <td class="text-left p-2">{{$agreement->AgreementType->name}}</td>
    </tr>
    <tr>
        <td class="text-right text-black-50">Продавец</td>
        <td class="text-left p-2">
            @if(!$agreement->seller->our_company)
                <a href="{{route('counterpartySummary', ['counterparty'=>$agreement->seller])}}"> {{$agreement->seller->name}} </a>
            @else
                {{$agreement->seller->name}}
            @endif    
        </td>
    </tr>
    <tr>
        <td class="text-right text-black-50">Покупатель</td>
        <td class="text-left p-2">
            @if(!$agreement->buyer->our_company)
                <a href="{{route('counterpartySummary', ['counterparty'=>$agreement->buyer])}}"> {{$agreement->buyer->name}} </a>
            @else
                {{$agreement->buyer->name}}
            @endif    

        </td>
    </tr>
    @if($agreement->project)
    <tr>
        <td class="text-right text-black-50">Проект</td>
        <td class="text-left p-2">
            @if(Gate::allows('s-projects'))
                <a href="{{route('projects.summary', ['project'=> $agreement->project])}}">
                    {{$agreement->project->name}}
                </a>
            @else
                {{$agreement->project->name}}
            @endif    
        </td>
    </tr>
    @endif
    <tr>
        <td class="text-right text-black-50">Описание</td>
        <td class="text-left p-2">{{$agreement->description}}</td>
    </tr>

    {{-- <tr>
        <td class="text-right text-black-50">Просроченная задолженность</td>
        <td class="text-left p-2">{{number_format($agreement->payments->where('payment_date',"<",now())->sum('amount')-$agreement->realPayments->where('payment_date',"<",now())->sum('amount'),2)}}</td> 
    </tr> --}}

    <tr>
        <td class="text-right text-black-50">Дата окончания</td>
        <td class="text-left p-2">{{\Carbon\Carbon::parse($agreement->date_close)->format('d.m.Y')}}</td>
    </tr>


    <tr>
        <td class="text-right text-black-50">Статус договора</td>
        <td class="text-left p-2">
            @if($agreement->real_date_close)
                Договора закрыт {{\Carbon\Carbon::parse($agreement->real_date_close)->format('d.m.Y')}}
            @else
                Действующий договор
            @endif
        </td>
    </tr>

    <tr>
        <td class="text-right text-black-50"></td>
        <td>
            @if(Gate::allows('e-agreements'))
                <a href="{{route('editAgreement',['agreement'=>$agreement])}}"
                   class="btn btn-outline-secondary" role="button" aria-pressed="true">Отредактировать</a>
            @endif
        </td>
    </tr>


</table>



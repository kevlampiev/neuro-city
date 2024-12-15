<div class="card bg-light p-3">
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Номер и дата</th>
            <th scope="col">Стороны по договору</th>
            <th scope="col">Дата завершения</th>
            <th scope="col"></th>
            <th scope="col"></th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        <tbody>

        @forelse($task->agreements as $agreement)

            <tr>
                <th scope="row">{{$loop->index+1}}</th>
                <td>
                    {{$agreement->name}} № {{$agreement->agr_number}} от {{Carbon\Carbon::parse($agreement->date_open)->format('d.m.y')}}
                </td>
                <td>
                    <p>{{$agreement->buyer->name}}</p>
                    <p>{{$agreement->seller->name}}</p>
                </td>
                <td > @if(isset($agreement->real_date_close)) 
                        {{Carbon\Carbon::parse($agreement->real_date_close)->format('d.m.y')}}
                      @else
                        --
                      @endif  
                </td>
                <td>
                    <a href="{{route('agreementSummary',['agreement'=>$agreement])}}">
                        &#9776;Карточка
                    </a>
                </td>
                <td>
                    <a href="{{route('detachAgreementFromTask', ['task'=>$task, 'agreement' => $agreement])}}">
                        &#10008;Открепить 
                    </a>    
                </td>
            </tr>

        @empty
            <td colspan="8" class="font-italic">
                Задача связана ни с одним договором
            </td>
        @endforelse
        </tbody>
    </table>
</div>

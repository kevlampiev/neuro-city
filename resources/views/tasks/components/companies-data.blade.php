<div class="card bg-light p-3">
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Наименование</th>
                <th scope="col">ИНН</th>
            <th scope="col">ADesk id</th>
            <th scope="col"></th>
            <th scope="col"></th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        <tbody>

        @forelse($task->companies as $company)

            <tr>
                <th scope="row">{{$loop->index+1}}</th>
                <td> {{$company->name}} </td>
                 <td> {{$company->inn}} </td>
                <td> {{$company->adesk_id}} </td>
                <td>
                    <a href="{{route('companySummary',['company'=>$company])}}">
                        &#9776;Карточка
                    </a>
                </td>
                <td>
                    <a href="{{route('detachCompanyFromTask', ['task'=>$task, 'company' => $company])}}">
                        &#10008;Открепить 
                    </a>    
                </td>
            </tr>

        @empty
            <td colspan="8" class="font-italic">
                Задача связана ни с одной компанией
            </td>
        @endforelse
        </tbody>
    </table>
</div>

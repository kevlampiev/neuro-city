<div class="row">
    <div class="col-md-12">


        <table class="table table-hover">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Тип договора</th>
                <th scope="col">Номер и дата</th>
                <th scope="col">Компания</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            @forelse($counterparty->agreements as $index => $agreement)
                <tr @if($agreement->real_date_close&&$agreement->real_date_close<=now()) class="text-black-50 agreement-close"@endif>
                    <th scope="row">{{$loop->index+1}}</th>
                    <td>{{$agreement->agreementType->name}}</td>
                    <td>№ {{$agreement->agr_number}}
                        от {{\Carbon\Carbon::parse($agreement->date_open)->format('d.m.Y')}} </td>
                    <td>{{$agreement->company->name}}</td>
                    <td>
                        <a href="{{route('admin.agreementSummary',['agreement'=>$agreement])}}">
                            &#9776;Карточка
                        </a>
                    </td>
                    <td>
                    </td>
                </tr>
            @empty
                <tr>
                    <th colspan="7">1</th>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@section("styles")
    <style>
        .agreement-close {
            text-decoration: line-through;
        }
    </style>
@endsection
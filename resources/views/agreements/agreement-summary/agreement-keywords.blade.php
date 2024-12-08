<table class="table">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">Список синонимов</th>
        <th scope="col"></th>
        <th scope="col"></th>
    </tr>
    </thead>
    <tbody>
    @forelse($keywords as $keyword)
        <tr>
            <th scope="row">{{$loop->index+1}}</th>
            <td>{{$keyword->name}}</td>

            <td>
                @if(Gate::allows('e-real_payment'))
                <a href="{{route('editAgreementKeyword', ['agreementKeyword'=>$keyword])}}">
                    &#9998;Изменить </a>
                @endif    
            </td>
            <td>
                @if(Gate::allows('e-real_payment'))
                <a href="{{route('deleteAgreementKeyword', ['agreementKeyword'=>$keyword])}}"
                   onclick="return confirm('Действительно удалить ключевое слово?')">
                    &#10008;Удалить </a>
                @endif     
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="4" class="text-secondary font-italic text-center"> Нет данных для отображения</td>
        </tr>

    @endforelse

    </tbody>
</table>

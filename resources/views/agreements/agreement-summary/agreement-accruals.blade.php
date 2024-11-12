<div class="col-md-12 p-4">
    <h4>Начисления по договору</h4>
    @if(Gate::allows('e-accruals'))
    <div class="row">
        <div class="col-md-12">
            <a class="btn btn-outline-info mr-2"
               href="{{route('accruals.create', ['agreement'=>$agreement])}}">новое начисление</a>
            {{-- <a class="btn btn-outline-info mr-2"
               href="{{route('admin.massAddaccruals', ['agreement'=>$agreement])}}">Добавить серию платежей</a> --}}
        </div>
    </div>
    @endif

    <table class="table table-hover">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Дата</th>
            <th scope="col">Сумма</th>
            <th scope="col">Статья</th>
            <th scope="col">Основание</th>
            <th scope="col"></th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
        
        @forelse($accruals as $accrual)
            <tr>
                <th scope="row">{{$loop->index+1}}</th>
                <td>{{\Carbon\Carbon::parse($accrual->date_open)->format('d.m.Y')}}</td>
                <td class="text-right">{{number_format($accrual->amount, 2, '.', ',')}}</td>
                <td class="text-left">{{$accrual->plItem->name}}</td>
                <td class="text-left">{{$accrual->description}}</td>

               
                <td>
                    @if(Gate::allows('e-accruals'))
                    <a href="{{route('accruals.edit', ['accrual' => $accrual])}}">
                        &#9998;Изменить </a>
                    @endif    
                </td>
                <td>
                    @if(Gate::allows('e-accruals'))
                    <a href="{{route('accruals.destroy', ['accrual' => $accrual])}}"
                       onclick="return confirm('Действительно удалить данные о начислении?')">
                        &#10008;Удалить </a>
                    @endif    
                </td>
            </tr>
        @empty
            <tr>
                <th colspan="6">Нет записей</th>
            </tr>
        @endforelse
        <tr>
            <th colspan="2">Итого</th>
            <th class="text-right">{{number_format($accruals->sum('amount'), 2)}}</th>
            <th class="text-left"></th>
            <th></th>
            <th></th>
        </tr>
        </tbody>
    </table>
   

</div>
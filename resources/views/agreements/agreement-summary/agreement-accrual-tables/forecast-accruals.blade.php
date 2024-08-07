<table class="table">
    <thead>
    <tr>
        <th scope="col">Номер п.п.</th>
        <th scope="col">Дата начисления</th>
        <th scope="col">Выполнено работ/реализовано</th>
        <th scope="col">цена единицы без НДС</th>
        <th scope="col">Начислено без НДС</th>
        <th scope="col">Комментарий</th>
        <th scope="col"></th>
            <th scope="col"></th>
    </tr>
    </thead>
    <tbody>


    @forelse($agreement->accruals->where('status','forecast') as $index=>$accrual)
        
        <tr class="text-secondary">        
            <td>{{$index+1}}</td>
            <td>{{\Carbon\Carbon::parse($accrual->accrual_date)->format('d.m.Y')}}</td>
            <td>{{number_format($accrual->volume,1,',')}} {{$accrual->unitOfMeasurement->name}}</td>
            <td>{{number_format($accrual->price,2,',')}} {{$accrual->currency}}</td>
            <td>{{number_format($accrual->volume * $accrual->price,2,',')}} {{$accrual->currency}}</td>
            <td>{{$accrual->description}}</td>
            <td>
                @if(Gate::allows('e-accruals'))
                    <a href="{{route('admin.editAgrAccrual', ['accrual' => $accrual, 'agreement' => $agreement])}}"> Изменить </a>
                @endif    
            </td>
            <td>
                @if(Gate::allows('e-accruals'))
                    <a href="{{route('admin.deleteAgrAccrual', ['accrual' => $accrual, 'agreement' => $agreement])}}"> Удалить </a>
                @endif
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="4">Нет начислений по договору</td>
        </tr>
    @endforelse


    </tbody>
</table>

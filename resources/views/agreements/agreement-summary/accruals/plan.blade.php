    <div class="row">
        <div class="col-md-12">
            <a class="btn btn-outline-info m-2"
               href="{{route('plan-accruals.add', ['agreement'=>$agreement])}}">
               <i class="bi bi-tools"></i>
               Новое начисление
            </a>
            {{-- <a class="btn btn-outline-info m-2"
               href="{{route('plan-accruals.mass-add', ['agreement'=>$agreement])}}">
               <i class="bi bi-cash-stack"></i>
               Добавить серию платежей
            </a>    --}}
        </div>
    </div>


    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Дата начисления</th>
                <th scope="col">Товар/Услуга</th>
                <th scope="col">Сумма </th>
                <th scope="col">Статья, проект</th>
                <th scope="col"></th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
        @forelse($agreement->planAccruals as $accrual)
            <tr>
                <th scope="row">{{$loop->index+1}}</th>
                <td>
                    @if(!$accrual->shifted_date||$accrual->initial_date==$accrual->shifted_date)
                        {{\Carbon\Carbon::parse($accrual->initial_date)->format('d.m.Y')}}
                    @else
                        {{\Carbon\Carbon::parse($accrual->initial_date)->format('d.m.Y')}} &rarr;
                        {{\Carbon\Carbon::parse($accrual->shifted_date)->format('d.m.Y')}}
                    @endif    
                </td>
                <td> {{$accrual->product->name}}</td>
                <td class="text-right">
                    {!! str_replace(' ', '&nbsp;', number_format($accrual->units_count, 2, ',', ' ')) !!} ед X 
                    {!! str_replace(' ', '&nbsp;', number_format($accrual->amount_per_unit, 2, ',', ' ')) !!} руб = 
                    {!! str_replace(' ', '&nbsp;', number_format($accrual->amount, 2, ',', ' ')) !!} руб
                </td>

                <td class="text-left">
                    {{$accrual->plItem->name}} {{$accrual->project?', '.$accrual->project->name:''}}
                </td>
                

                <td>
                    @if(Gate::allows('e-accruals'))
                        <a href="{{ route('plan-accruals.edit', ['accrual' => $accrual]) }}"
                        class="text-decoration-none text-primary p-1"
                        data-bs-toggle="tooltip"
                        title="Отредактировать запись"
                        style="transition: background-color 0.3s;"
                        onmouseover="this.style.backgroundColor='#f8f9fa';"
                        onmouseout="this.style.backgroundColor='';">
                            &#9998; 
                        </a>
                    @endif    
                </td>
                <td>
                    @if(Gate::allows('e-accruals'))
                        <a href="{{ route('plan-accruals.destroy', ['accrual' => $accrual]) }}"
                        class="text-decoration-none text-danger p-1"
                        data-bs-toggle="tooltip"
                        title="Удалить запись"
                        style="transition: background-color 0.3s;"
                        onclick="return confirm('Действительно удалить данные о платеже?');"
                        onmouseover="this.style.backgroundColor='#f8f9fa';"
                        onmouseout="this.style.backgroundColor='';">
                            &#10008;
                        </a>
                    @endif    
                </td>
            </tr>
        @empty
            <tr>
                <th colspan="6">Нет записей</th>
            </tr>
        @endforelse
        <tr>
            <th colspan="2">Всего</th>
            <th class="text-right" colspan="4">
                {{-- {!! str_replace(' ', '&nbsp;', number_format($agreement->planaccruals->sum('units_count'), 1, ',', ' ')) !!} ед на сумму  --}}
                    {!! str_replace(' ', '&nbsp;', number_format($agreement->planaccruals->sum('amount'), 2, ',', ' ')) !!}
            </th>
            <th class="text-left"></th>
            <th></th>
            <th></th>
        </tr>
        </tbody>
    </table>


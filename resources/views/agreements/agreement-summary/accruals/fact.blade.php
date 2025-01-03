@if(Gate::allows('e-accruals'))
<div class="row">
    <div class="col-md-12">
        <a class="btn btn-outline-info m-2"
            href="{{route('accruals.create', ['agreement'=>$agreement])}}">
            <i class="bi bi-wrench-adjustable"></i>
            Добавить начисление
        </a>
    </div>
</div>
@endif

<table class="table table-hover">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">Дата</th>      
        <th scope="col">Товар/услуга</th>
        <th scope="col">Стоимость</th>
        <th scope="col">Статья, Проект</th>
        <th scope="col">Комментарий</th>
.        <th scope="col"></th>
        <th scope="col"></th>
    </tr>
    </thead>
    <tbody>
    @forelse($agreement->accruals as $accrual)
        <tr>
            {{-- @dd($accrual); --}}
            <th scope="row">{{$loop->index+1}}</th>
            <td>{{\Carbon\Carbon::parse($accrual->date_open)->format('d.m.Y')}}</td>
            <td> -- </td>
            <td class="text-right">
                {!! str_replace(' ', '&nbsp;', number_format($accrual->amount, 2, ',', ' ')) !!}
            </td>
            <td class="text-left">
                {{$accrual->pl_item_name}}
                @if($accrual->project_name)
                    , {{$accrual->project_name}}
                @endif
            </td>
            <td class="text-left">
                {{ implode(' ', array_slice(explode(' ', $accrual->description), 0, 7)) }}{{ str_word_count($accrual->description) > 10 ? '...' : '' }} ...
            </td>
            <td>
                @if(Gate::allows('e-accruals'))
                        <a href="{{route('accruals.edit', ['accrual' => $accrual])}}"
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
                    <form action="{{ route('accruals.destroy', ['accrual' => $accrual]) }}" method="POST" style="display: inline;" 
                        onsubmit="return confirm('Действительно удалить данные о начислении?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background: none; border: none; color: red; cursor: pointer;">
                            &#10008;
                        </button>
                    </form>
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
            {!! str_replace(' ', '&nbsp;', number_format($agreement->accruals->sum('amount'), 2, ',', ' ')) !!}
        </th>
        <th class="text-left"></th>
        <th></th>
        <th></th>
    </tr>
    </tbody>
</table>



<div class="d-none d-lg-block pt-2 pl-lg-4 bg-secondary">

    <a class="btn btn-outline-light mr-1 border-0" href="{{route('userTasks', ['user' => Auth::user()])}}">
        <i class="bi bi-calendar3-range"></i> Мои задачи
    </a>

    @if(Gate::allows('s-agreements'))
        <a class="btn btn-outline-light mr-1 border-0" href="{{route('agreements')}}">
            <i class="bi bi-journal-minus"></i> Договоры
        </a>
    @endif

    {{-- <a class="btn btn-outline-secondary mr-1 border-0"
       href="{{route('admin.userTasks', ['user' => auth()->user()])}}">
        <i class="bi bi-list-task"></i> Мои задачи
    </a> --}}

    @if(Gate::allows('s-counterparty'))
        <a class="btn btn-outline-light mr-1 border-0"
        href="{{route('counterparties')}}">
            <i class="bi bi-people"></i> Контрагенты
        </a>
    @endif

    {{-- @if(Gate::allows('s-real_payment'))
        <a class="btn btn-outline-secondary mr-1 border-0"
        href="{{route('admin.realPayments')}}">
            <i class="bi bi-cash-coin"></i> Платежи
        </a>
    @endif --}}

</div>

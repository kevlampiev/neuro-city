<div class="d-none d-lg-block pt-2 pl-lg-4 bg-secondary">
    <button 
        class="btn news-button position-relative" 
        type="button" 
        data-bs-toggle="offcanvas" 
        data-bs-target="#offcanvasWithBothOptions" 
        aria-controls="offcanvasWithBothOptions" 
        title="Новости">
        <i class="{{ count(Auth::user()->unreadNotifications) > 0 ? 'bi bi-bell-fill animated-bell' : 'bi bi-bell' }}"></i>
        
        @if(count(Auth::user()->unreadNotifications) > 0) 
            <!-- Жёлтая метка -->
            <span 
                class="position-absolute top-0 start-100 translate-middle p-1 bg-warning border border-light rounded-circle">
            </span>
        @endif
    </button>

    <a class="btn btn-outline-light border-0" href="{{ route('userTasks', ['user' => Auth::user()]) }}">
        <i class="bi bi-calendar3-range"></i> Мои задачи
    </a>

    @if(Gate::allows('s-agreements'))
        <a class="btn btn-outline-light border-0" href="{{ route('agreements') }}">
            <i class="bi bi-journal-minus"></i> Договоры
        </a>
    @endif

    @if(Gate::allows('s-counterparty'))
        <a class="btn btn-outline-light border-0" href="{{ route('counterparties') }}">
            <i class="bi bi-people"></i> Контрагенты
        </a>
    @endif
</div>

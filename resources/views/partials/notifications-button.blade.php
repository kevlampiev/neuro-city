<i class="{{ count(Auth::user()->unreadNotifications) > 0 ? 'bi bi-bell-fill animated-bell' : 'bi bi-bell' }}"></i>
        
@if(count(Auth::user()->unreadNotifications) > 0) 
    <!-- Жёлтая метка -->
    <span 
        class="position-absolute top-0 start-100 translate-middle p-1 bg-warning border border-light rounded-circle">
    </span>
@endif

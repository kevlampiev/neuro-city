<ul class="list-group">
    @forelse($notifications as $notification)
        <li class="list-group-item p-3 {{ is_null($notification->read_at) ? 'bg-light fw-bold' : '' }}">
            <a 
                href="{{ route('notifications.markAsRead', $notification->id) }}" 
                class="text-decoration-none d-flex justify-content-between align-items-start w-100">
                <div>
                    <span class="{{ is_null($notification->read_at) ? 'text-primary' : 'text-secondary' }}">
                        {{ $notification->data['sender'] }}
                    </span>
                    <span class="text-muted">
                        {{ $notification->data['subject'] }}
                    </span>
                </div>
            </a>
        </li>
    @empty
        <li class="list-group-item text-center text-muted">
            Нет сообщений
        </li>
    @endforelse
</ul>

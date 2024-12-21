<div 
    class="offcanvas offcanvas-start" 
    data-bs-scroll="true" 
    tabindex="-1" 
    id="offcanvasWithBothOptions" 
    aria-labelledby="offcanvasWithBothOptionsLabel">
  <div class="offcanvas-header bg-primary text-white">
    <h5 class="offcanvas-title" id="offcanvasWithBothOptionsLabel">
        Последняя активность
    </h5>
    <button 
        type="button" 
        class="btn-close btn-close-white" 
        data-bs-dismiss="offcanvas" 
        aria-label="Закрыть">
    </button>
  </div>
  <div class="offcanvas-body">
    <div id="notifications-container">
        <ul class="list-group">
        @forelse(auth()->user()->notifications->sortByDesc('created_at')->take(30) as $notification)
            <li class="list-group-item p-3 
                {{ is_null($notification->read_at) ? 'bg-light fw-bold' : '' }}">
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
    </div>    
  </div>
</div>

<style>
    a.text-decoration-none {
    color: inherit; /* Наследует текущий цвет текста */
    text-decoration: none; /* Убирает подчеркивание */
    }

    a.text-decoration-none:hover {
        color: #0d6efd; /* Синий цвет текста при наведении */
        text-decoration: underline; /* Подчеркивание при наведении */
    }

    /* Убедимся, что кнопка с колокольчиком имеет прозрачный фон */
    .news-button {
        background-color: transparent;
        border: none; /* Убираем границу */
        color: inherit; /* Используем текущий цвет текста */
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative; /* Для метки */
        transition: transform 0.3s ease;
    }

    .news-button:hover {
        transform: scale(1.1); /* Увеличение при наведении */
    }

    /* Жёлтая метка */
    .news-button .position-absolute {
        width: 10px;
        height: 10px;
        box-shadow: 0 0 8px rgba(255, 193, 7, 0.6);
        animation: glow 1.5s infinite;
    }

    /* Эффект мерцания метки */
    @keyframes glow {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.6;
        }
    }

    /* Контейнер панели */
    .d-none.d-lg-block {
        display: flex; /* Делаем элементы расположенными в строку */
        flex-wrap: nowrap; /* Запрещаем перенос элементов */
        align-items: center; /* Выравнивание элементов по вертикали */
        background-color: var(--bs-secondary); /* Устанавливаем фон для панели */
        padding: 10px; /* Добавляем отступы */
        gap: 10px; /* Пробелы между элементами */
    }

    /* Кнопки-ссылки на панели */
    .d-none.d-lg-block .btn {
        display: inline-flex; /* Сохраняем флекс-стиль */
        align-items: center;
        justify-content: center;
        color: #fff; /* Светлый текст */
        border: 1px solid transparent; /* Убираем стандартную рамку */
        background: transparent; /* Прозрачный фон */
        padding: 5px 10px;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .d-none.d-lg-block .btn:hover {
        background-color: rgba(255, 255, 255, 0.2); /* Легкий фон при наведении */
        color: #ffd700; /* Золотой текст при наведении */
    }

</style>

<script defer>
    function refreshNotifications() {
        fetch('{{ route('notifications.refresh') }}')
            .then(response => response.json())
            .then(data => {
                document.getElementById('notifications-container').innerHTML = data.htmlList;
                document.getElementById('notification-button').innerHTML = data.htmlButton;
               console.log('updated')
            })
            .catch(error => console.error('Ошибка обновления уведомлений:', error));
    }

    // Обновление каждые 5 минут
    setInterval(refreshNotifications, 3*60* 1000);
</script>
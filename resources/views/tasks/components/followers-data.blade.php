<div class="card bg-light p-3">
    <div class="d-flex flex-wrap gap-3 justify-content-start">
        @forelse($task->followers as $user)
            <div class="position-relative text-center" style="width: 170px;">
                <!-- Кнопка отключения подписчика -->
                @if(\Illuminate\Support\Facades\Auth::user()->id == $task->user_id)
                    <a href="{{ route('detachTaskFollower', ['task' => $task, 'user' => $user]) }}"
                       class="btn btn-close position-absolute top-0 end-0 m-1"
                       onclick="return confirm('Действительно отключить подписчика?')">
                    </a>
                @endif

                <!-- Изображение пользователя -->
                <img src="{{ $user->photo?asset('storage/img/avatars'.$user->photo):asset('noimg.png') }}" 
                     alt="{{ 'Изображение ' . $user->name }}" 
                     class="img-thumbnail rounded-circle" 
                     style="width: 150px; height: 150px; object-fit: cover;">

                <!-- Имя пользователя -->
                <div class="mt-2">
                    <strong>{{ $user->name }}</strong>
                </div>
            </div>
        @empty
            <i>Никто не подписан на задачу</i>
        @endforelse
    </div>
</div>

<div class="card bg-light p-3">
    @forelse($task->followers as $user)
        <div>
            <div class="pl-2 mb-2">
                <ul class="list-group">
                    <li class="list-group-item">
                        {{$user->name}}
                        @if(\Illuminate\Support\Facades\Auth::user()->id == $task->user_id)
                            <a class="btn btn-outline-info"
                               href="{{route('detachTaskFollower',['task'=>$task, 'user' => $user])}}"
                               onclick="return confirm('Действительно отключить подписчика?')">
                                Отключить
                            </a>
                        @endif
                    </li>
                </ul>

            </div>

        </div>
    @empty
        <i>Никто не подписан а задачу</i>
    @endforelse
</div>

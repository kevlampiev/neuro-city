{{-- <div class="row">
    <div class="col-md-8">
        <div class="card bg-light">
            <table class="table">
                <tbody>
                <tr>
                    <td class="text-right">Идентификатор</td>
                    <td><i> #{{$task->id}} </i></td>
                </tr>

                <tr>
                    <td class="text-right">Формулировка</td>
                    <td class="text-monospace
                                    {{$task->importance=='high'?'text-danger':''}}
                                     {{$task->importance=='low'?'text-secondary':''}}
                ">
                        <strong><i> {{$task->subject}} </i></strong>
                    </td>
                </tr>

                <tr>
                    <td class="text-right">Статус задачи</td>
                    <td class="text-monospace"><i>
                            @if($task->terminate_status=='cancel')
                                <span class="text-secondary">
                                            Отменена {{\Carbon\Carbon::parse($task->terminate_date)->format('d.m.Y')}}
                                        </span>
                            @elseif($task->terminate_status=='complete')
                                <span class="text-success">
                                            Выполнена {{\Carbon\Carbon::parse($task->terminate_date)->format('d.m.Y')}}
                                        </span>
                            @else
                                Выполняется ...
                            @endif

                        </i></td>
                </tr>

                <tr>
                    <td class="text-right">Сроки исполнения</td>
                    <td
                        class="text-monospace @if(($task->due_date<\Carbon\Carbon::now())&&(!$task->terminate_date))
                                           text-danger
                                        @elseif((\Carbon\Carbon::parse($task->due_date)->diffInDays(now())<3)&&(!$task->terminate_date))
                                           text-warning
                                        @endif
                    ">
                        <i> {{\Carbon\Carbon::parse($task->start_date)->format('d.m.Y')}} -
                            {{\Carbon\Carbon::parse($task->due_date)->format('d.m.Y')}}</i>
                    </td>
                </tr>
                <tr>
                    <td class="text-right">Постановщик задачи</td>
                    <td class="text-monospace"><i>{{$task->user->name}} </i></td>
                </tr>

                <tr>
                    <td class="text-right">Исполнитель</td>
                    <td class="text-monospace"><i>{{$task->performer->name}} </i></td>
                </tr>
                <tr>
                    <td class="text-right">Дополнительная информация</td>
                    <td class="text-monospace"><i>{{$task->description}} </i></td>
                </tr>
                @if($task->parent_task_id&&(Auth::user()->id==$task->user_id))
                    <tr>
                        <td class="text-right">Родительская задача</td>
                        <td class="text-monospace"><i><a
                                    href="{{route('taskCard', ['task' => \App\Models\Task::find($task->parent_task_id)])}}">
                                    {{\App\Models\Task::find($task->parent_task_id)->subject}}
                                </a> </i></td>
                    </tr>
                @endif
                
                </tbody>
            </table>
        </div>

    </div>
    <div class="col-md-4">
        <div class="card bg-light">
            <table class="table">
                <tbody>
                <tr>
                    <td>Подписчики <br>
                        <ul class="list-group">
                            @forelse($task->followers as $follower)
                                <li class="list-group-item bg-light text-secondary font-italic">
                                    @include('partials.avatar-mini', ['user'=>$follower]) &thinsp; {{$follower->name}}
                                </li>
                            @empty
                                нет подписчиков
                            @endforelse
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td>Последние сообщения <br>
                       @forelse($task->messages->sortByDesc('date_created')->take(5) as $message)
                            <div class="text-secondary font-italic m-2 p-2 bg-white">
                                {{ implode(' ', array_slice(explode(' ', html_entity_decode(strip_tags($message->description))), 0, 10)) }}...
                            </div>
                        @empty
                            <div class="text-muted text-center">
                                Нет сообщений
                            </div>
                        @endforelse
                    </td>
                </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>
 --}}

{{-- НОВЫЙ ВИД --}}

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title text-primary">Детали задачи</h5>
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <th class="text-end text-muted">Идентификатор</th>
                            <td>#{{ $task->id }}</td>
                        </tr>
                        <tr>
                            <th class="text-end text-muted">Формулировка</th>
                            <td class="fw-bold {{ $task->importance == 'high' ? 'text-danger' : ($task->importance == 'low' ? 'text-secondary' : '') }}">
                                {{ $task->subject }}
                            </td>
                        </tr>
                        <tr>
                            <th class="text-end text-muted">Статус задачи</th>
                            <td>
                                @if($task->terminate_status === 'cancel')
                                    <span class="badge bg-secondary">
                                        Отменена {{ \Carbon\Carbon::parse($task->terminate_date)->format('d.m.Y') }}
                                    </span>
                                @elseif($task->terminate_status === 'complete')
                                    <span class="badge bg-success">
                                        Выполнена {{ \Carbon\Carbon::parse($task->terminate_date)->format('d.m.Y') }}
                                    </span>
                                @else
                                    <span class="badge bg-warning text-dark">Выполняется...</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="text-end text-muted">Сроки исполнения</th>
                            <td class="{{ ($task->due_date < now() && !$task->terminate_date) ? 'text-danger' : ((now()->diffInDays($task->due_date) < 3 && !$task->terminate_date) ? 'text-warning' : '') }}">
                                {{ \Carbon\Carbon::parse($task->start_date)->format('d.m.Y') }} - {{ \Carbon\Carbon::parse($task->due_date)->format('d.m.Y') }}
                            </td>
                        </tr>
                        <tr>
                            <th class="text-end text-muted">Постановщик</th>
                            <td>@include('partials.avatar-mini', ['user'=>$task->user]) {{ $task->user->name }}</td>
                        </tr>
                        <tr>
                            <th class="text-end text-muted">Исполнитель</th>
                            <td> @include('partials.avatar-mini', ['user'=>$task->performer]) {{ $task->performer->name }}</td>
                        </tr>
                        <tr>
                            <th class="text-end text-muted">Описание</th>
                            <td>{{ $task->description }}</td>
                        </tr>
                        @if($task->parent_task_id && auth()->id() == $task->user_id)
                            <tr>
                                <th class="text-end text-muted">Родительская задача</th>
                                <td>
                                    <a href="{{ route('taskCard', ['task' => $task->parent_task_id]) }}" class="text-decoration-none text-primary">
                                        {{ \App\Models\Task::find($task->parent_task_id)->subject }}
                                    </a>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title text-primary">Подписчики</h5>
                <ul class="list-group list-group-flush">
                    @forelse($task->followers as $follower)
                        <li class="list-group-item d-flex align-items-center">
                            @include('partials.avatar-mini', ['user' => $follower])
                            <span class="ms-2">{{ $follower->name }}</span>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">Нет подписчиков</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="card mt-3 shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title text-primary">Последние сообщения</h5>
                @forelse($task->messages->sortByDesc('date_created')->take(5) as $message)
                    <div class="mb-2 p-2 bg-light rounded shadow-sm">
                        {{ implode(' ', array_slice(explode(' ', strip_tags($message->description)), 0, 10)) }}...
                    </div>
                @empty
                    <div class="text-muted text-center">Нет сообщений</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

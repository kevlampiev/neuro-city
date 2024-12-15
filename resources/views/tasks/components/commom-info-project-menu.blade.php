@if($task->user==Auth::user())
    <div class="btn-group" role="group" aria-label="Basic outlined example">
        <a class="btn btn-outline-info" href="{{route('editTask', ['task' => $task])}}">Редактировать</a>

        @if(!$task->terminate_date)
            <div class="btn-group" role="group">
                <button id="btnGroupDrop1" type="button" class="btn btn-outline-info dropdown-toggle"
                        data-bs-toggle="dropdown" aria-expanded="false">
                    Завершить проект
                </button>
                <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <li><a class="dropdown-item text-decoration-line-through"
                           href="{{route('markTaskAsCanceled', ['task'=> $task])}}"
                           onclick="return confirm('Это действие отменит данную задачу и все дочерние задачи. Продолжать?')">
                            Отменить
                        </a></li>
                    <li><a class="dropdown-item text-success"
                           href="{{route('markTaskAsDone', ['task'=> $task])}}"
                           onclick="return confirm('Это действие пометит данную задачу и все дочерние задачи как выполненные. Продолжать?')">
                            Выполнить
                        </a></li>
                </ul>
            </div>
        @else
            <a class="btn btn-outline-info"
               href="{{route('markTaskAsRunning', ['task' => $task])}}">
                Возобновить проект
            </a>
        @endif

    </div>
@endif

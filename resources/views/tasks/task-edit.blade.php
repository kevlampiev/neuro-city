@extends('layouts.big-form')

@section('title')
    Администратор|Редактирование данных о задаче
@endsection

@section('content')
    <h3> @if ($task->id)
            Изменение задачи
        @else
            Добавить новую задачу
        @endif</h3>
    <form action="{{$task->id?route('editTask', $task->id):route('addTask')}}" method="POST"
          enctype="multipart/form-data">
        @csrf


        <div class="row">
            <div class="col-md-10">


                <input type="hidden" name="user_id" value="{{$task->user_id}}">
                <!-- Поле ввода имени задачи -->
                <div class="input-group mb-3">
                    <span class="input-group-text" id="subject">Формулировка задачи</span>
                    <input type="text"
                           class="form-control {{$errors->has('subject')?'is-invalid':''}}"
                           aria-describedby="subject"
                           placeholder="Введите название задачи" name="subject"
                           value="{{$task->subject}}">
                </div>
                @if ($errors->has('subject'))
                    <div class="alert alert-danger">
                        <ul class="p-0 m-0">
                            @foreach($errors->get('subject') as $error)
                                <li class="m-0 p-0"> {{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Поле ввода родительской задачи -->
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">Родительская задача</span>
                    <select name="parent_task_id"
                            id="parent-tasks-select"
                            class="form-control {{$errors->has('parent_task_id')?'is-invalid':''}}"
                            aria-describedby="basic-addon1"
                            data-live-search="true"
                    >
                        <option value="" {{(!$task->parent_task_id) ? 'selected' : ''}}></option>
                        @foreach ($tasks as $parentTask)
                            <option
                                value="{{$parentTask->id}}"
                                {{($parentTask->id == $task->parent_task_id) ? 'selected' : ''}}
                            >
                                {{$parentTask->subject}}
                            </option>
                        @endforeach
                    </select>
                </div>
                @if ($errors->has('parent_task_id'))
                    <div class="alert alert-danger">
                        <ul class="p-0 m-0">
                            @foreach($errors->get('parent_task_id') as $error)
                                <li class="m-0 p-0"> {{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif



                <!-- Поля ввода срока исполнения задачи -->
                <div class="input-group mb-3">
                    <span class="input-group-text" id="start_date">Срок исполнения</span>
                    <input type="date"
                           class="form-control {{$errors->has('start_date')?'is-invalid':''}}"
                           aria-describedby="start_date"
                           placeholder="Дата начала" name="start_date"
                           value="{{\Carbon\Carbon::parse($task->start_date)->toDateString()}}">
                    <input type="date"
                           class="form-control {{$errors->has('due_date')?'is-invalid':''}}"
                           aria-describedby="due_date"
                           placeholder="Дата завершения" name="due_date"
                           value="{{\Carbon\Carbon::parse($task->due_date)->toDateString()}}">
                </div>
                @if ($errors->has('start_date'))
                    <div class="alert alert-danger">
                        <ul class="p-0 m-0">
                            @foreach($errors->get('start_date') as $error)
                                <li class="m-0 p-0"> {{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if ($errors->has('due_date'))
                    <div class="alert alert-danger">
                        <ul class="p-0 m-0">
                            @foreach($errors->get('due_date') as $error)
                                <li class="m-0 p-0"> {{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Поле ввода уровня важности -->

                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">Важность</span>
                    <select name="importance"
                            class="form-control {{$errors->has('importance')?'is-invalid':''}}"
                            aria-describedby="basic-addon1">
                        @foreach ($importances as $key=>$importance)
                            <option
                                value="{{$key}}" {{($key == $task->importance) ? 'selected' : ''}}>
                                {{$importance}}
                            </option>
                        @endforeach
                    </select>
                </div>
                @if ($errors->has('importance'))
                    <div class="alert alert-danger">
                        <ul class="p-0 m-0">
                            @foreach($errors->get('importance') as $error)
                                <li class="m-0 p-0"> {{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif



                <!-- Поле ввода дополнительной информации -->
                <div class="form-group">
                    <label for="description">Дополнительная информация</label>
                    <textarea class="form-control {{$errors->has('description')?'is-invalid':''}}"
                              id="description"
                              rows="6" name="description">{{$task->description}}</textarea>
                </div>
                @if ($errors->has('description'))
                    <div class="alert alert-danger">
                        <ul class="p-0 m-0">
                            @foreach($errors->get('description') as $error)
                                <li class="m-0 p-0"> {{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


                <!-- Поле ввода исполнителя -->
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">Исполнитель задачи</span>
                    <select name="task_performer_id"
                            id="user-select"
                            class="form-control {{$errors->has('user_id')?'is-invalid':''}}"
                            aria-describedby="basic-addon1"
                            data-live-search="true">
                        @foreach ($users as $user)
                            <option
                                value="{{$user->id}}" {{($user->id == $task->task_performer_id) ? 'selected' : ''}}>
                                {{$user->name}}
                            </option>
                        @endforeach
                    </select>
                </div>
                @if ($errors->has('task_performer_id'))
                    <div class="alert alert-danger">
                        <ul class="p-0 m-0">
                            @foreach($errors->get('task_performer_id') as $error)
                                <li class="m-0 p-0"> {{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


                <details>
                    <summary>
                        Дополнительные поля
                    </summary>
                    
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="{{$task->hidden_task}}"
                               id="flexCheckIndeterminate" name="hidden_task"
                            {{old('hidden_task')==1||$task->hidden_task==1?'checked="checked"' : ''}}>
                        <label class="form-check-label" for="flexCheckIndeterminate">
                            Скрыть задачу из списков задач пользователей
                        </label>
                    </div>


                </details>

            </div>
        </div>

        <div class="mt-10">
            <button type="submit" class="btn btn-primary">
                @if ($task->id)
                    Изменить
                @else
                    Добавить
                @endif
            </button>
            <a class="btn btn-secondary" href="{{session('previous_url', route('userTasks', ['user'=> Auth::user()]))}}">Отмена</a>
        </div>
    </form>

@endsection

@section('scripts')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#user-select').select2();
            $('#parent-tasks-select').select2();
        })
    </script>
@endsection

@section('styles')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>

    <!-- Добавление пользовательских стилей -->
    <style>
        h3 {
            color: black; /* Установить черный цвет текста для заголовка */
        }

        select.form-control,
        input.form-control,
        textarea.form-control {
            color: black; /* Установить черный цвет текста для полей ввода */
        }

        .select2-container--default .select2-selection--single {
            color: black; /* Установить черный цвет текста для select2 */
        }

        .select2-container--default .select2-results__option {
            color: black; /* Черный цвет текста для выпадающего списка select2 */
        }
    </style>
@endsection
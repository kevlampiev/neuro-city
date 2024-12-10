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
            <div class="col-md-8">


                <input type="hidden" name="user_id" value="{{$task->user_id}}">
                <!-- Поле ввода имени задачи -->
                <div class="mb-3">
                    <label for="nameImput" class="form-label">Наименование задачи</label>
                    <input type="text"
                            id="nameInput"
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
                <div class="mb-3">
                    
                    <label for="parent-tasks-select" class="form-label">Родительская задача</label>
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
                <div class="mb-3">
                    
                    <label for="startDateInput" class="form-label">Срок исполнения</label>
                    <div class="d-flex gap-2">
                        <input type="date"
                            id="startDateInput"
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

                <div class="mb-3">
                    <label for="importanceInput" class="form-label">Важность</label>
                    <select name="importance"
                            id="importanceInput"
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
                <div class="mb-3">
                    <label for="user-select">Исполнитель задачи</label>
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
        
        label {
            color: black; /* Установить черный цвет текста для заголовка */
            font-weight: 100;
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

        #search-input {
            width: 150px;
        }

        .d-flex {
            display: flex !important;
        }
    </style>
@endsection


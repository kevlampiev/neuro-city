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
                @include('partials.error', ['field' => 'subject'])

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
                @include('partials.error', ['field' => 'parent_task_id'])



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
                @include('partials.error', ['field' => 'start_date'])
                @include('partials.error', ['field' => 'due_date'])
                
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
                @include('partials.error', ['field' => 'importance'])

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
                @include('partials.error', ['field' => 'task_performer_id'])             


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

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

<script>
    
    document.addEventListener('DOMContentLoaded', () => {
    new Choices('#parent-tasks-select', {
        searchEnabled: true,
        placeholderValue: 'Выберите задачу',
        shouldSort: false,
    });
    
    new Choices('#user-select', {
        searchEnabled: true,
        placeholderValue: 'Выберите исполнителя',
        shouldSort: false,
    });
});
</script>
@endsection


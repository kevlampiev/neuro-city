@extends('layouts.big-form')

@section('title')
    Администратор | Сообщение
@endsection

@section('content')
    <h3>
        @if ($message->id)
            Изменение сообщения
        @else
            Новое сообщение
        @endif
    </h3>
    <form method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-12 mb-3">
                <h4>По задаче: {{$task->subject}}</h4>
                <input type="hidden" name="user_id" value="{{$message->user_id}}">
                <input type="hidden" name="task_id" value="{{$task->id}}">

                <!-- Поле ввода описания -->
                <div class="form-group">
                    <textarea class="form-control {{$errors->has('description') ? 'is-invalid' : ''}}"
                              id="description"
                              rows="6"
                              name="description">{{$message->description}}</textarea>
                </div>
                @include('partials.error', ['field' => 'description'])
                
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
            <a class="btn btn-secondary" href="{{url()->previous()}}">Отмена</a>
        </div>
    </form>
@endsection

@section('scripts')
    <!-- CKEditor 5 -->
    <script src="https://cdn.ckeditor.com/ckeditor5/38.1.1/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#description'), {
                toolbar: [
                    'heading', '|',
                    'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|',
                    'blockQuote', 'undo', 'redo'
                ],
                ckfinder: {
                    uploadUrl: '/laravel-filemanager/upload?type=Files&_token={{ csrf_token() }}'
                }
            })
            .catch(error => {
                console.error(error);
            });
    </script>
@endsection

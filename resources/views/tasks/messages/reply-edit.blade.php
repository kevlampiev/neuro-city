@extends('layouts.big-form')

@section('title')
    Сообщение
@endsection

@section('content')
    <div class="card shadow-sm border-0 mt-4">
        <div class="card-header bg-primary text-white">
            <h3 class="m-0">
                {{ $message->id ? 'Изменение сообщения' : 'Новое сообщение' }}
            </h3>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="user_id" value="{{ $message->user_id }}">
                <input type="hidden" name="reply_to_message_id" value="{{ $message->reply_to_message_id }}">
                <input type="hidden" name="task_id" value="{{ $message->task_id }}">

                <div class="mb-4">
                    <label for="description" class="form-label fw-bold">
                        Ответ на сообщение
                    </label>
                    <textarea 
                        class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" 
                        id="description" 
                        rows="6" 
                        name="description"
                        placeholder="Введите текст сообщения...">{{ $message->description }}</textarea>
                    @if ($errors->has('description'))
                        <div class="invalid-feedback d-block">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->get('description') as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary me-3">
                        Отмена
                    </a>
                    <button type="submit" class="btn btn-primary">
                        {{ $message->id ? 'Изменить' : 'Добавить' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
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
                        'blockQuote', 'imageUpload', 'mediaEmbed', '|',
                        'undo', 'redo'
                    ],
                    ckfinder: {
                        uploadUrl: '/documents/upload', // Путь к методу uploadFile
                        options: {
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Добавляем CSRF-токен для безопасности
                            }
                        }
                    },
                    simpleUpload: {
                        uploadUrl: '/documents/upload', // Путь для загрузки файлов
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    }
                })
                .catch(error => {
                    console.error(error);
                });
    </script>
@endsection

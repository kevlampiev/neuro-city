@extends('layouts.big-form')

@section('title')
    Сообщение
@endsection

@section('content')
    <h3>
        @if ($message->id)
            Изменение ответа
        @else
            Создать ответ
        @endif
    </h3>
    <form id="multipleDocumentsForm" method="POST"
     {{-- action="{{ route('addTaskMessage', ['task'=>$task]) }}"  --}}
     enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-12 mb-3">
                <input type="hidden" name="user_id" value="{{$message->user_id}}">
                <input type="hidden" name="reply_to_message_id" value="{{$message->reply_to_message_id}}">
                <input type="hidden" name="task_id" value="{{$message->task_id}}">

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

        <!-- Кнопка для открытия Dropzone -->
        <button type="button" id="toggleDropzoneButton" class="btn btn-light mb-3">
            <i class="bi bi-paperclip"></i>
            Прикрепить файлы
        </button>

        <!-- Dropzone -->
        <div class="dropzone-container" id="dropzoneContainer" style="display: none;">
            <div class="dropzone" id="documentsDropzone"></div>
        </div>

        <div class="mt-10">
            <button type="submit" class="btn btn-primary">
                @if ($message->id)
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

     <script src="https://cdn.jsdelivr.net/npm/dropzone@5/dist/min/dropzone.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/dropzone@5/dist/min/dropzone.min.css">
    
    <script>
        Dropzone.autoDiscover = false;

        // Инициализация Dropzone
        const dropzone = new Dropzone("#documentsDropzone", {
            url: "{{ route('documentUpload') }}",
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            paramName: "file",
            maxFilesize: 5, // MB
            parallelUploads: 5,
            uploadMultiple: false,
            autoProcessQueue: true,
            acceptedFiles: '.pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.ppt,.pptx,.rtf',
            addRemoveLinks: true,
            dictDefaultMessage: 'Перетащите файлы сюда или нажмите для выбора',
            dictRemoveFile: 'Удалить файл',
        });

        // Сохранение информации о загруженных файлах
        dropzone.on("success", function (file, response) {
            const form = document.getElementById('multipleDocumentsForm');

            // Скрытое поле для имени файла
            const inputFilename = document.createElement('input');
            inputFilename.type = 'hidden';
            inputFilename.name = 'uploaded_files[]';
            inputFilename.value = response.filename;
            form.appendChild(inputFilename);

            // Скрытое поле для оригинального имени файла
            const inputOriginalName = document.createElement('input');
            inputOriginalName.type = 'hidden';
            inputOriginalName.name = `original_names[${response.filename}]`;
            inputOriginalName.value = response.original_name;
            form.appendChild(inputOriginalName);
        });

        // Удаление файла из скрытых полей при удалении из Dropzone
        dropzone.on("removedfile", function (file) {
            const inputs = document.querySelectorAll(`input[name='uploaded_files[]']`);
            inputs.forEach((input) => {
                if (input.value === file.upload.filename) {
                    input.remove();
                }
            });
        });

        // Показ/скрытие Dropzone
        document.getElementById('toggleDropzoneButton').addEventListener('click', function () {
            const dropzoneContainer = document.getElementById('dropzoneContainer');
            if (dropzoneContainer.style.display === 'none') {
                dropzoneContainer.style.display = 'block';
            } else {
                dropzoneContainer.style.display = 'none';
            }
        });
    </script>
@endsection

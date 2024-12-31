@extends('layouts.big-form')

@section('title')
    Договор | Загрузка нескольких документов
@endsection

@section('content')
    <h3>Добавить документы для договора: {{$agreement->name}}</h3>

    <form id="multipleDocumentsForm" method="POST" enctype="multipart/form-data" 
    action="{{ route('addAgreementManyDocuments', ['agreement' => $agreement->id]) }}">
        @csrf
        <input type="hidden" name="agreement_id" value="{{ $agreement->id }}">
        <div class="dropzone" id="documentsDropzone"></div>

        <button type="button" id="submitButton" class="btn btn-primary mt-3">Загрузить</button>
        
        <a class="btn btn-secondary mt-3" href="{{ route('agreementSummary', ['agreement' => $agreement->id, 'page' => 'documents']) }}">Отмена</a>
    </form>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/dropzone@5/dist/min/dropzone.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/dropzone@5/dist/min/dropzone.min.css">
    
    <script>
        Dropzone.autoDiscover = false;

        const dropzone = new Dropzone("#documentsDropzone", {
            url: "{{ route('documentUpload') }}", // Маршрут для загрузки файлов
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            paramName: "file", // Имя параметра для файла
            maxFilesize: 5, // MB
            parallelUploads: 5,
            uploadMultiple: false, // По одному файлу
            autoProcessQueue: true, // Файлы будут загружаться автоматически, но форма не отправляется
            acceptedFiles: '.pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.ppt,.pptx,.rtf',
            addRemoveLinks: true,
            dictDefaultMessage: 'Перетащите файлы сюда или нажмите для выбора',
            dictRemoveFile: 'Удалить файл',
        });

        // Сохранение информации о загруженных файлах
        dropzone.on("success", function (file, response) {
            console.log('File uploaded:', response);

            const form = document.getElementById('multipleDocumentsForm');

            // Скрытое поле для имени файла
            const inputFilename = document.createElement('input');
            inputFilename.type = 'hidden';
            inputFilename.name = 'uploaded_files[]';
            inputFilename.value = response.filename; // Уникальное имя файла
            form.appendChild(inputFilename);

            // Скрытое поле для оригинального имени файла
            const inputOriginalName = document.createElement('input');
            inputOriginalName.type = 'hidden';
            inputOriginalName.name = 'original_names[]';
            inputOriginalName.value = response.original_name; // Оригинальное имя
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

        // Предотвращение автоматического закрытия формы
        document.getElementById('submitButton').addEventListener('click', function () {
            // Проверяем, добавлены ли файлы
            const uploadedFiles = document.querySelectorAll(`input[name='uploaded_files[]']`);
            if (uploadedFiles.length === 0) {
                alert("Добавьте хотя бы один файл перед отправкой формы.");
                return;
            }

            // Отправляем форму
            document.getElementById('multipleDocumentsForm').submit();
        });

        // Обработка ошибок
        dropzone.on("error", function (file, message) {
            alert("Ошибка загрузки файла: " + message);
        });
    </script>
@endsection

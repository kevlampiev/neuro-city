@extends('layouts.big-form')

@section('title')
    Договор | Загрузка нескольких документов
@endsection

@section('content')
    <h3>Добавить документы для договора: {{$agreement->name}}</h3>

    <form id="multipleDocumentsForm" method="POST" enctype="multipart/form-data" 
    action="{{ route('addAgreementManyDocuments', ['agreement' => $agreement->id]) }}">
        @csrf
        <div class="dropzone" id="documentsDropzone"></div>

        <button type="button" id="submitButton" class="btn btn-primary mt-3">Загрузить</button>
        
        <a class="btn btn-secondary" href="{{ route('agreementSummary', ['agreement' => $agreement->id, 'page' => 'documents']) }}">Отмена</a>
    </form>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/dropzone@5/dist/min/dropzone.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/dropzone@5/dist/min/dropzone.min.css">
    
    <script>
        Dropzone.autoDiscover = false;

        // Инициализация Dropzone
        const dropzone = new Dropzone("#documentsDropzone", {
            url: "{{ route('addAgreementManyDocuments', ['agreement' => $agreement->id]) }}",
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            paramName: "documents[]",
            maxFilesize: 5, // MB
            parallelUploads: 5,
            uploadMultiple: true,
            acceptedFiles: '.pdf,.doc,.docx',
            addRemoveLinks: true,
            autoProcessQueue: false, // Отключаем автоматическую загрузку
            dictDefaultMessage: 'Перетащите файлы сюда или нажмите для выбора',
            dictRemoveFile: 'Удалить файл',
        });

        // Обработчик нажатия на кнопку "Загрузить"
        document.getElementById('submitButton').addEventListener('click', function () {
            if (dropzone.getQueuedFiles().length > 0) {
                dropzone.processQueue(); // Начинаем загрузку файлов
            } else {
                alert("Добавьте хотя бы один файл для загрузки.");
            }
        });

        // Перенаправление после успешной загрузки
        dropzone.on("queuecomplete", function () {
            window.location.href = "{{ route('agreementSummary', ['agreement' => $agreement->id, 'page' => 'documents']) }}";
        });

        // Обработка ошибок загрузки
        dropzone.on("error", function (file, message) {
            alert("Ошибка загрузки файла: " + message);
        });
    </script>
@endsection

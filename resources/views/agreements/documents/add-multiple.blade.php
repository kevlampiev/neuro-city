@extends('layouts.big-form')

@section('title')
    Договор | Загрузка нескольких документов
@endsection

@section('content')
    <h3>Добавить документы для договора: {{$agreement->name}}</h3>

    <form id="multipleDocumentsForm" method="POST" enctype="multipart/form-data" action="{{ route('storeMultipleDocuments', ['agreement' => $agreement->id]) }}">
        @csrf
        <div class="dropzone" id="documentsDropzone"></div>

        <button type="submit" class="btn btn-primary mt-3">Загрузить</button>
        <a class="btn btn-secondary" href="{{ route('agreementSummary', ['agreement' => $agreement->id, 'page' => 'documents']) }}">Отмена</a>
    </form>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/dropzone@5/dist/min/dropzone.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/dropzone@5/dist/min/dropzone.min.css">
    
    <script>
        Dropzone.autoDiscover = false;

        const dropzone = new Dropzone("#documentsDropzone", {
            url: "{{ route('storeMultipleDocuments', ['agreement' => $agreement->id]) }}",
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            paramName: "documents[]",
            maxFilesize: 5, // MB
            parallelUploads: 5,
            uploadMultiple: true,
            acceptedFiles: '.pdf,.doc,.docx',
            addRemoveLinks: true,
            dictDefaultMessage: 'Перетащите файлы сюда или нажмите для выбора',
            dictRemoveFile: 'Удалить файл',
        });

        document.getElementById('multipleDocumentsForm').addEventListener('submit', function (e) {
            e.preventDefault();
            dropzone.processQueue();
        });

        dropzone.on("success", function () {
            window.location.href = "{{ route('agreementSummary', ['agreement' => $agreement->id, 'page' => 'documents']) }}";
        });
    </script>
@endsection

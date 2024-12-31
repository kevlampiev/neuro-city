@extends('layouts.big-form')

@section('title')
    Договор | Добавить файл
@endsection    

@section('content')
    <h3>
        @if ($document->id)
            Изменение данных
        @else
            Добавить новый документ
        @endif
    </h3>
    <form method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-md-12">

                {{-- Связанный договор --}}
                <div class="mb-3">
                    <label for="task" class="form-label">Задача</label>
                    <select 
                        name="task_id" 
                        id="task" 
                        class="form-control {{ $errors->has('task_id') ? 'is-invalid' : '' }}"
                    >
                        @foreach ($tasks as $task)
                            <option 
                                value="{{ $task->id }}" 
                                {{ ($task->id == $task_id) ? 'selected' : '' }}
                            >
                                #{{ $task->id }} {{ $task->subject }}
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('task_id'))
                        <div class="alert alert-danger mt-2">
                            <ul class="mb-0">
                                @foreach($errors->get('task_id') as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                {{-- Название файла --}}
                <div class="mb-3">
                    <label for="description" class="form-label">Наименование документа</label>
                    <input 
                        type="text" 
                        id="description" 
                        name="description" 
                        class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" 
                        value="{{ $document->description }}" 
                        placeholder="Введите наименование документа"
                    >
                    @if ($errors->has('description'))
                        <div class="alert alert-danger mt-2">
                            <ul class="mb-0">
                                @foreach($errors->get('description') as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                {{-- Загрузка файла --}}
                <div class="mb-3">
                    <label class="form-label">Файл документа</label>
                    <div class="d-flex align-items-center">
                        @if($document->file_name)
                            <a 
                                href="{{ route('user.filePreview', ['filename' => $document->file_name]) }}" 
                                class="btn btn-outline-primary me-3"
                                target="_blank"
                            >
                                <i class="bi bi-file-earmark-pdf"></i> Открыть файл
                            </a>
                        @endif
                        <span id="file-status" class="me-3">
                            {{ $document->file_name ? "Файл доступен для скачивания" : "Файл отсутствует" }}
                        </span>
                        <label class="btn btn-outline-secondary" for="inputGroupFile01">
                            <i class="bi bi-upload"></i> Загрузить новый файл
                        </label>
                        <input 
                            type="file" 
                            class="form-control d-none" 
                            id="inputGroupFile01" 
                            name="document_file" 
                            accept="application/pdf"
                        >
                    </div>
                    @if ($errors->has('document_file'))
                        <div class="alert alert-danger mt-2">
                            <ul class="mb-0">
                                @foreach($errors->get('document_file') as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

            </div>
        </div>

        {{-- Кнопки действия --}}
        <button type="submit" class="btn btn-primary">
            @if ($document->id)
                Изменить
            @else
                Добавить
            @endif
        </button>
        <a class="btn btn-secondary" href="{{ session('previous_url', route('taskCard', ['task' => $task, 'page' => 'documents'])) }}">
            Отмена
        </a>
    </form>
@endsection

@section('scripts')
    <script>
        document.getElementById('inputGroupFile01').addEventListener('change', function() {
            const fileName = this.files[0] ? this.files[0].name : 'Файл отсутствует';
            document.getElementById('file-status').textContent = `Выбран файл: ${fileName}`;
        });
    </script>
@endsection

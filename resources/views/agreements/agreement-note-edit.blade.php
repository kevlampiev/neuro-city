@extends('layouts.big-form')

@section('title')
    Администратор|Изменение заметки
@endsection

@section('content')
    <h3> @if ($agreementNote->id)
            Редактирование заметки
        @else
            Добавить заметку
        @endif</h3>
    <form method="POST">
        @csrf
            <div class="form-group">
                <label for="input-agreement">Договор</label>
                <input type="hidden"
                       id="agreement_id" name="agreement_id" value="{{$agreement->id}}">
                <input type="hidden"
                       id="note_id" name="id" value="{{$agreementNote->id}}">       
                <input type="text"
                       id="input-agreement" name="agreement" value="{{$agreement->name.' '.$agreement->agr_number}}" disabled>
                 @if ($errors->has('agreement_id'))
                    <div class="alert alert-danger">
                        <ul class="p-0 m-0">
                            @foreach($errors->get('agreement-id') as $error)
                                <li class="m-0 p-0"> {{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif       
            </div>

            <div class="form-group">
                <label for="description">Текст заметки</label>
                <textarea class="form-control {{$errors->has('description') ? 'is-invalid' : ''}}"
                        id="description"
                        rows="13" name="description" maxlength="255">{{$agreementNote->description}}</textarea>
                <small id="charCount" class="form-text text-muted">
                    0 / 255 символов
                </small>
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


            <button type="submit" class="btn btn-primary">
                @if ($agreementNote->id)
                    Изменить
                @else
                    Добавить
                @endif
            </button>
            <a class="btn btn-secondary"
               href="{{route('agreementSummary',['agreement'=>$agreement, 'page' => 'notes'])}}">
                Отмена
            </a>
    </form>

@endsection

@section("scripts")
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const textarea = document.getElementById('description');
            const charCountDisplay = document.getElementById('charCount');
            const maxLength = 255;

            // Функция для обновления счетчика символов
            const updateCharCount = () => {
                const currentLength = textarea.value.length;
                charCountDisplay.textContent = `${currentLength} / ${maxLength} символов`;

                // Добавляем класс для красного текста, если символов больше 255
                if (currentLength > maxLength) {
                    charCountDisplay.classList.add('text-danger');
                } else {
                    charCountDisplay.classList.remove('text-danger');
                }
            };

            // Инициализация начального значения
            updateCharCount();

            // Событие на ввод текста в textarea
            textarea.addEventListener('input', updateCharCount);
        });
    </script>
@endsection

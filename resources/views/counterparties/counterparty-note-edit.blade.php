@extends('layouts.big-form')

@section('title')
    Администратор|Изменение заметки
@endsection

@section('content')
    <h3> @if ($counterpartyNote->id)
            Редактирование заметки
        @else
            Добавить заметку
        @endif</h3>
    <form
        {{-- @if($counterpartyNote->id)
            action="{{route('admin.editCounterpartyNote', ['counterpartyNote' => $counterpartyNote->id])}}"
        @else
            action="{{route('admin.addCounterpartyNote', ['counterparty' => $counterpartyNote->counterparty->id])}}"
        @endif --}}
        method="POST">
        @csrf
        <form>
            <div class="form-group">
                <label for="inputType">Контрагент</label>
                <input type="hidden"
                       id="counterparty_id" name="company_id" value="{{$counterpartyNote->company_id}}">
                <input type="text"
                       id="input-counterparty" name="counterparty" value="{{$company->name}}" disabled>
            </div>

            <div class="form-group">
                <label for="description">Текст заметки</label>
                <textarea class="form-control {{$errors->has('note_body')?'is-invalid':''}}"
                          id="note_body"
                          rows="13" name="note_body">{{$counterpartyNote->note_body}}</textarea>
            </div>
            @if ($errors->has('note_body'))
                <div class="alert alert-danger">
                    <ul class="p-0 m-0">
                        @foreach($errors->get('note_body') as $error)
                            <li class="m-0 p-0"> {{$error}}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <button type="submit" class="btn btn-primary">
                @if ($counterpartyNote->id)
                    Изменить
                @else
                    Добавить
                @endif
            </button>
            <a class="btn btn-secondary"
               href="{{route('counterpartySummary',['counterparty'=>$company, 'page' => 'notes'])}}">
                Отмена
            </a>

        </form>

    </form>

@endsection

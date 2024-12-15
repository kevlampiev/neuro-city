@extends('layouts.big-form')

@section('title')
    Добавить договор
@endsection

@section('content')
    <h3> Добавить связанный договор с задачей {{$task->subject}}</h3>
    <form method="POST">
        @csrf
        <input type="hidden" name="task_id" value={{$task->id}}>
        <div class="input-group mb-3">
            <label for="users"></label>
            <select name="agreement_id" id="agreements">
                @foreach ($agreements as $agreement)
                    <option
                        value="{{$agreement->id}}">
                        {{$agreement->name}} № {{$agreement->agr_number}} от {{\Carbon\Carbon::parse($agreement->date_open)->format('dd.mm.YYYY')}}
                        между {{$agreement->buyer->name }} и {{$agreement->seller->name }}
                    </option>
                @endforeach
            </select>
        </div>


        <button type="submit" class="btn btn-primary">
            Добавить
        </button>
        <a class="btn btn-secondary" href="{{route('taskCard',['task'=>$task, 'page'=>'agreements'])}}">Отмена</a>
    </form>

@endsection

@section('scripts')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script>
        
        document.addEventListener('DOMContentLoaded', () => {
        new Choices('#agreements', {
            searchEnabled: true,
            placeholderValue: 'Выберите договор по имени или участнику',
            shouldSort: false,
        });
        
    });
    </script>
@endsection



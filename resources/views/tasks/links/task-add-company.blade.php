@extends('layouts.big-form')

@section('title')
    Связать с компаний
@endsection

@section('content')
    <h3> Связать компанию с задачей {{$task->subject}}</h3>
    <form method="POST">
        @csrf
        <input type="hidden" name="task_id" value={{$task->id}}>
        <div class="input-group mb-3">
            <label for="companies"></label>
            <select name="company_id" id="companies">
                @foreach ($companies as $company)
                    <option
                        value="{{$company->id}}">
                        {{$company->name}} {{$company->inn?"ИНН: ".$company->inn:""}} {{$company->adesk_id?"Adesk: ".$company->adesk_id:""}}
                        {{$company->our_company?"Компания группы":""}}
                    </option>
                @endforeach
            </select>
        </div>


        <button type="submit" class="btn btn-primary">
            Добавить
        </button>
        <a class="btn btn-secondary" href="{{route('taskCard',['task'=>$task, 'page'=>'companies'])}}">Отмена</a>
    </form>

@endsection

@section('scripts')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script>
        
        document.addEventListener('DOMContentLoaded', () => {
        new Choices('#companies', {
            searchEnabled: true,
            placeholderValue: 'Выберите компанию',
            shouldSort: false,
        });
        
    });
    </script>
@endsection



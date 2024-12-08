@extends('layouts.big-form')

@section('title')
    Администратор|Изменение ключевого слова для договора
@endsection

@section('content')
    <h3> @if ($agreementKeyword->id)
            Редактирование ключевого слова
        @else
            Добавить ключевое слово
        @endif</h3>
    <form
        @if($agreementKeyword->id)
            action="{{route('editAgreementKeyword', ['agreementKeyword' => $agreementKeyword->id])}}"
        @else
            action="{{route('addAgreementKeyword', ['agreement' => $agreementKeyword->agreement->id])}}"
        @endif
        method="POST">
        @csrf
        <form>
            <div class="form-group">
                <label for="inputType">Договор</label>
                <input type="hidden"
                       id="agreement_id" name="agreement_id" value="{{$agreementKeyword->agreement_id}}">
                <input type="text"
                       id="input-agreement" name="agreement" value="{{$agreementKeyword->agreement->name}}" disabled>
            </div>

            <div class="form-group">
                <label for="input-keyword">Ключевое слово</label>
                <input type="text" class="form-control {{$errors->has('name')?'is-invalid':''}}"
                       id="input-keyword" name="name" value="{{$agreementKeyword->name}}">
            </div>
            @if ($errors->has('name'))
                <div class="alert alert-danger">
                    <ul class="p-0 m-0">
                        @foreach($errors->get('name') as $error)
                            <li class="m-0 p-0"> {{$error}}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <button type="submit" class="btn btn-primary">
                @if ($agreementKeyword->id)
                    Изменить
                @else
                    Добавить
                @endif
            </button>
            <a class="btn btn-secondary"
               href="{{route('admin.agreementSummary',['agreement'=>$agreementKeyword->agreement_id, 'page' => 'keywords'])}}">
                Отмена
            </a>

        </form>

    </form>

@endsection

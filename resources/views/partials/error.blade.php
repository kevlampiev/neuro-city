@if ($errors->has($field))
    <div class="alert alert-danger">
        <ul class="p-0 m-0">
            @foreach($errors->get($field) as $error)
                <li class="m-0 p-0">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
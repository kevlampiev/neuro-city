@extends('layouts.big-form')


@section('content')
    <div class="row justify-content-center">
        <div class="col-mb-8">
            <h3> Редактирование личной информации</h3>
            <form action="#" method="POST"  enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class = "col-md-6">
                            <div class="form-group">
                                <label for="inputName">Имя </label>
                                <input type="text"
                                    class="{{($errors->has('name')?'form-control is-invalid':'form-control')}}"
                                    id="inputName" placeholder="Введите имя" name="name"
                                    value="{{$user->name}}">
                            </div>
                            @if($errors->has('name'))
                                <div class="alert alert-danger">
                                    <ul class="p-0 m-0">
                                        @foreach($errors->get('name') as $error)
                                            <li class="m-0 p-0"> {{$error}}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                                
                            <div class="form-group">
                                <label for="inputEMail">e-mail</label>
                                <input type="email"
                                    class="{{($errors->has('email')?'form-control is-invalid':'form-control')}}"
                                    id="inputEMail" placeholder="name@server.ru" name="email"
                                    value="{{$user->email}}">
                            </div>
                            @if($errors->has('email'))
                                <div class="alert alert-danger">
                                    <ul class="p-0 m-0">
                                        @foreach($errors->get('email') as $error)
                                            <li class="m-0 p-0"> {{$error}}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="form-group">
                                <label for="inputPhone">Номер телефона</label>
                                <input type="tel"
                                    class="{{($errors->has('phone_number')?'form-control is-invalid':'form-control')}}"
                                    id="inputPhone" name="phone_number"
                                    placeholder="(XXX)-XXX-XXXX"
                                    value="{{$user->phone_number}}"
                                >
                            </div>
                            @if($errors->has('phone_number'))
                                <div class="alert alert-danger">
                                    <ul class="p-0 m-0">
                                        @foreach($errors->get('phone_number') as $error)
                                            <li class="m-0 p-0"> {{$error}}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
 
                            <div class="form-group">
                                <label for="inputBirthday">День рождения</label>
                                <input type="date"
                                    class="{{($errors->has('birthday')?'form-control is-invalid':'form-control')}}"
                                    id="inputBirthday" name="birthday"
                                    value="{{$user->birthday}}"
                                >
                            </div>
                            @if($errors->has('birthday'))
                                <div class="alert alert-danger">
                                    <ul class="p-0 m-0">
                                        @foreach($errors->get('birthday') as $error)
                                            <li class="m-0 p-0"> {{$error}}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                    </div>       
                    
                    <div class="col-md-6">
                        <h5>Изображение для аватара</h5>
                        <div onclick="document.getElementById('loadImgBtn').click()">
                            @if(!$user->photo)
                                <img src=   "https://aristokratrest.com/files/rublevbar/image/no_product.jpg" alt="No photo"
                                    class="rounded-circle float-start border-info" style="width: 200px; height:200px; object-fit: cover;" id="img-viewer">
                            @else
                                {{-- @dd(route('avatar.get', ['filename' => $user->photo])) --}}
                                 <img src="{{ route('avatar.get', ['filename' => $user->photo]) }}" alt="User photo"
                                    class="rounded-circle float-start border-info"
                                    style="width: 200px; height:200px; object-fit: cover;" id="img-viewer">   

            
                            @endif
                        </div>    
                        
                    </div>
                </div>

                <div class="row mt-2">
                    <div class = "col-md-6">
                        {{-- <a class="btn btn-outline-secondary" href="{{route('password.expired')}}"> Изменить пароль </a> --}}
                        <a class="btn btn-outline-secondary" href="#"> <i class="bi bi-incognito"></i> Изменить пароль </a>

                    </div>
                    <div class = "col-md-6">
                        <div>
                            <a class="btn btn-outline-secondary" href="#" onclick="document.getElementById('loadImgBtn').click()" > <i class="bi bi-image"></i> Изменить изображение </a>
                        </div>
                        <div class="input-group mb-3">
                            <input type="file" class="form-control-file" id="loadImgBtn" name="img_file"
                            accept="image/*" style="display:none">
                        </div>    

                    </div>
                </div>    

                <div class="pt-5  "> 
                    <button type="submit" class="btn btn-primary">
                        Изменить информацию
                    </button>
                    <a class="btn btn-secondary" href="{{session('previous_url',route('home'))}}">Отмена</a>
                </div>

            </form>
        </div>
    </div>

@endsection

@section('scripts')

    <script
        src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
        crossorigin="anonymous"></script>

    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    $('#img-viewer').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#loadImgBtn").change(function () {
            readURL(this);
        });


    </script>
@endsection
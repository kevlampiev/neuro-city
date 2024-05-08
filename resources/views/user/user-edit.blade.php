@extends('layouts.admin')

@section('title')
    Администратор|Редактирование пользователя
@endsection

@section('content')
    <h3> @if ($user->id)
            Изменение пользователя
        @else
            Добавление пользователя
        @endif</h3>
    <form method="POST" enctype="multipart/form-data">
        @csrf
        <form>
            <div class="row">
                <div class="col-md-8">
                    <input type="hidden" name="id" value="{{$user->id}}">
                    <div class="form-group">
                        <label for="inputName">Имя пользователя</label>
                        <input type="text" class="form-control {{$errors->has('name')?'is-invalid':''}}" id="inputName"
                               placeholder="Введите имя" name="name"
                               value="{{$user->name}}">
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

                    <div class="form-group">
                        <label for="birthday">Дата рождения</label>
                        <input type="date"
                               id="birthday"
                               class="form-control {{$errors->has('birthday')?'is-invalid':''}}"
                               aria-describedby="model"
                               placeholder="Введите дату рождения" name="birthday"
                               value="{{$user->birthday}}">
                    </div>
                    @if ($errors->has('birthday'))
                        <div class="alert alert-danger">
                            <ul class="p-0 m-0">
                                @foreach($errors->get('birthday') as $error)
                                    <li class="m-0 p-0"> {{$error}}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif


                    <div class="form-group">
                        <label for="inputEMail">e-mail</label>
                        <input type="email" class="form-control {{$errors->has('email')?'is-invalid':''}}"
                               id="inputEMail" placeholder="name@server.ru" name="email"
                               value="{{$user->email}}">
                    </div>
                    @if ($errors->has('email'))
                        <div class="alert alert-danger">
                            <ul class="p-0 m-0">
                                @foreach($errors->get('email') as $error)
                                    <li class="m-0 p-0"> {{$error}}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @php  $roles = ['admin', 'user', 'manager']; @endphp
                    <div class="form-group">
                        <label for="inputRole">Роль</label>
                        <select name="role" class="form-control {{$errors->has('role')?'is-invalid':''}}"
                            {{Auth::user()->id===$user->id?'disabled':''}}>
                            @foreach ($roles as $role)
                                <option
                                    value="{{$role}}" {{($role == $user->role) ? 'selected' : ''}}>
                                    {{$role}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @if ($errors->has('role'))
                        <div class="alert alert-danger">
                            <ul class="p-0 m-0">
                                @foreach($errors->get('role') as $error)
                                    <li class="m-0 p-0"> {{$error}}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="phone_number">Телефон</label>
                        <input type="phone_number"
                               class="form-control {{$errors->has('phone_number')?'is-invalid':''}}"
                               id="phone_number" placeholder="+7 (ХХХ) ХХХ-ХХХХ" name="phone_number"
                               value="{{$user->phone_number}}">
                    </div>
                    @if ($errors->has('phone_number'))
                        <div class="alert alert-danger">
                            <ul class="p-0 m-0">
                                @foreach($errors->get('phone_number') as $error)
                                    <li class="m-0 p-0"> {{$error}}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="frc_id">Входитв ЦФО</label>
                        <select name="frc_id"
                                class="form-control {{$errors->has('frc_id')?'is-invalid':''}}">
                            @foreach ($frcs as $frc)
                                <option
                                    value="{{$frc->id}}" {{($user->frc_id == $frc->id) ? 'selected' : ''}}>
                                    {{$frc->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @if ($errors->has('frc_id'))
                        <div class="alert alert-danger">
                            <ul class="p-0 m-0">
                                @foreach($errors->get('frc_id') as $error)
                                    <li class="m-0 p-0"> {{$error}}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="company_id">Компания группы</label>
                        <select name="company_id" class="form-control ">
                            @foreach ($companies as $company)
                                <option
                                    value="{{$company->id}}" {{($user->company_id == $company->id) ? 'selected' : ''}}>
                                    {{$company->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @if ($errors->has('company_id'))
                        <div class="alert alert-danger">
                            <ul class="p-0 m-0">
                                @foreach($errors->get('company_id') as $error)
                                    <li class="m-0 p-0"> {{$error}}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="post">Должность</label>
                        <input type="post"
                               class="form-control {{$errors->has('post')?'is-invalid':''}}"
                               id="post" name="post"
                               value="{{$user->post}}">
                    </div>
                    @if ($errors->has('post'))
                        <div class="alert alert-danger">
                            <ul class="p-0 m-0">
                                @foreach($errors->get('post') as $error)
                                    <li class="m-0 p-0"> {{$error}}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                </div>

                <div class="col-md-4">
                    <h6>Фото/Аватар </h6>
                    <div class="card" style="width: 18rem;">
                        <img
                            @if($user->photo)
                                src="{{asset(config('paths.users.get','storage/img/users/').$user->photo)}}"
                            class="card-img-top" alt="..."
                            @else
                                src="{{asset('unknown_user.jpg')}}" class="card-img-top" alt="..."
                            @endif
                            id="img-viewer">
                        <div class="card-body" onclick="document.getElementById('inputGroupFile01').click()">
                        </div>
                        <a class="btn btn-outline-secondary"
                           onclick="document.getElementById('inputGroupFile01').click()">Изменить изображение</a>
                    </div>

                    <div class="input-group mb-3">
                        <input type="file" class="form-control-file" id="inputGroupFile01" name="photo_file"
                               accept="image/*" style="display:none">
                    </div>

                </div>

            </div>
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">
                        @if ($user->id)
                            Изменить
                        @else
                            Добавить
                        @endif
                    </button>
                    <a class="btn btn-secondary" href="{{route('admin.users')}}">Отмена</a>
                </div>
            </div>


        </form>

    </form>

@endsection


@section('scripts')
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    $('#img-viewer').attr('src', e.target.result);
                    $('#pts_tmp_path').attr('value', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#inputGroupFile01").change(function () {
            readURL(this);
        });


    </script>
@endsection

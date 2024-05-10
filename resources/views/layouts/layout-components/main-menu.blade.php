
<nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="background-color: #e3f2fd;">
    <div class="container-fluid">
        {{-- <a class="navbar-brand" href="{{ route('admin.main') }}"> --}}
        <a class="navbar-brand" href="/">

            <img src="{{asset('logo.png')}}" alt="" width="30" height="24" class="d-inline-block align-text-top">
            {{config('app.name')}}
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#"
                       id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Дроиды
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#">Список дроидов</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item disabled" href="#">Производство</a></li>
                    </ul>
                </li>


                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#"
                       id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Договоры/расчеты
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#">Договоры</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="#">Состояние расчетов по
                                компаниям</a></li>
                        <li><a class="dropdown-item" href="#">Состояние расчетов по
                                контрагентам</a></li>
                        <li><a class="dropdown-item" href="#">Получить
                                кредитно-лизинговый портфель</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="#">Предстоящие платежи</a>
                        </li>
                        <li><a class="dropdown-item" href="#">Платежный календарь на 2
                                недели</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="#">Предстоящие платежи
                                12 месяцев</a></li>
                    </ul>
                </li>


                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        Справочники
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#">Компании группы</a></li>
                        <li><a class="dropdown-item" href="#">Центры финансовой
                                ответственности</a></li>
                       
                        <li>
                        <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="#">Типы договоров</a></li>
                        <li><a class="dropdown-item" href="#">Компании</a></li>
                        
                        @if (Auth::user()->is_superuser==true)
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="{{route('users')}}">Пользователи</a></li>
                            <li><a class="dropdown-item" href="{{route('roles')}}">Роли в системе</a></li>
                        @endif

                    </ul>
                </li>

            </ul>
            <form class="d-flex" method="GET" action="#">
                <input class="form-control me-2" type="search" placeholder="глобальный поиск ..." aria-label="Search"
                       name="globalSearch">
                <button class="btn btn-outline-info" type="submit">Искать</button>
            </form>
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @include('layouts.layout-components.user-data-group')
            </ul>

        </div>

    </div>
</nav>

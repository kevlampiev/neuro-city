

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>@yield('title')</title>
    <link rel="SHORTCUT ICON" href="{{asset('logo.png')}}" type="image/x-icon">

     <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    @yield("styles")
</head>

   

<body class="bg-primary ">
    <div class="bg-main-image spread">
        @include("layouts.layout-components.main-menu")
        @include("layouts.layout-components.quick-access-toolbar")


        <main class="py-4">
            @if(session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
           @endif

            {{-- @if(count(auth()->user()->unreadNotifications)>0)
                <div class="alert alert-info">
                    <a href="{{route('admin.main')}}">&#9993; Для Вас есть новые уведомления </a>
                    <span class="badge bg-info">{{count(auth()->user()->unreadNotifications)}}</span>
                </div>
            @endif --}}

            @if(session()->has('error'))
                <div class="alert alert-danger">
                    {{ session()->get('error') }}
                </div>
            @endif

           
            @yield('content')
        </main>
    </div>
    <script src="script.js"></script>
</body>


</html>

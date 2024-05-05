<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>@yield('title')</title>
    <link rel="SHORTCUT ICON" href="{{asset('logo.png')}}" type="image/x-icon">
    
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    @yield("styles")
</head>

   

<body class="bg-primary">

    <div> </div>
    <div class="bg-primary" > 
        lorem   icon        
    </div>


    <script src="script.js"></script>
</body>


</html>

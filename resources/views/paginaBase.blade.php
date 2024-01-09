<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>VCounter</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
        <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    </head>
    <body class="container">
        <header>
            <div class="row">
                <div class="col-3 text-center">
                    <img
                        class="bg-light shadow rounded"
                        src="{{url('/img/faciteclogo.png')}}"
                        alt="Logo FACITEC"
                        style="width:100px; height:90px;">
                </div>
                <div class="col position-relative">
                    <h3 class="position-absolute top-50 start-50 translate-middle text-center"
                        style="width: 100%">Sistema de conteo de vehículos</h3>
                </div>
                <div class="col-3 text-center">
                    <img
                        class="bg-light shadow rounded"
                        src="{{url('/img/mapylogo.png')}}"
                        alt="Logo FACITEC"
                        style="width:100px; height:90px;">
                </div>
            </div>
            <hr>
            <nav>
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link inicio" aria-current="page" href="/">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link acerca" href="/about">Acerca del proyecto</a>
                    </li>
                </ul>
            </nav>
            <hr>
        </header>
        <div id="content-container">
            @yield('contenido')
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
        <script>
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
        </script>
    </body>
</html>

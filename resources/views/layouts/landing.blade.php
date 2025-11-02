<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'NaijaEdu-Pact') }}</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    
    <style>
        /* Custom Styles */
        body { display: flex; min-height: 100vh; flex-direction: column; }
        main { flex: 1 0 auto; }
        .hero {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('https://images.unsplash.com/photo-1523240795612-9a054b0db644?q=80&w=2070');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 80px 0;
        }
        .section { padding: 40px 0; }
        .progress { background-color: #c5cae9; }
        .progress .determinate { background-color: #3f51b5; }
    </style>
</head>
<body>
    <div id="app">
        <nav class="white" role="navigation">
            <div class="nav-wrapper container">
                <a id="logo-container" href="{{ route('landing') }}" class="brand-logo black-text">{{ config('app.name', 'NaijaEdu-Pact') }}</a>
                <ul class="right hide-on-med-and-down">
                    <li><a href="#projects" class="black-text">Explore Projects</a></li>
                    @guest
                        <li><a href="{{ route('login') }}" class="waves-effect waves-light btn indigo">Login</a></li>
                        <li><a href="{{ route('register') }}" class="waves-effect waves-light btn-flat">Register</a></li>
                    @else
                        <li><a href="{{ route('home') }}" class="waves-effect waves-light btn indigo">Dashboard</a></li>
                    @endguest
                </ul>

                <ul id="nav-mobile" class="sidenav">
                    <li><a href="#projects">Explore Projects</a></li>
                    @guest
                        <li><a href="{{ route('login') }}">Login</a></li>
                        <li><a href="{{ route('register') }}">Register</a></li>
                    @else
                        <li><a href="{{ route('home') }}">Dashboard</a></li>
                    @endguest
                </ul>
                <a href="#" data-target="nav-mobile" class="sidenav-trigger"><i class="material-icons black-text">menu</i></a>
            </div>
        </nav>

        <main>
            <div class="container">
                @if (session('success'))
                    <div class="card-panel green lighten-4 green-text text-darken-4">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="card-panel red lighten-4 red-text text-darken-4">{{ session('error') }}</div>
                @endif
                @if ($errors->any())
                    <div class="card-panel red lighten-4 red-text text-darken-4">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
            @yield('content')
        </main>

        <footer class="page-footer indigo darken-2">
            <div class="container">
                <div class="row">
                    <div class="col l6 s12">
                        <h5 class="white-text">NaijaEdu-Pact</h5>
                        <p class="grey-text text-lighten-4">Connecting alumni with their alma maters for a better tomorrow.</p>
                    </div>
                </div>
            </div>
            <div class="footer-copyright">
                <div class="container">
                    © {{ date('Y') }} NaijaEdu-Pact. All rights reserved.
                </div>
            </div>
        </footer>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var elems = document.querySelectorAll('.sidenav');
            M.Sidenav.init(elems);
        });
    </script>
</body>
</html>
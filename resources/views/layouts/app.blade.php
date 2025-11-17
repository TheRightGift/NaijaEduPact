<!doctype html>
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

    <script src="https://cdn.tiny.cloud/1/{{ config('services.tinymce.api_key') }}/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    @stack('styles')
</head>
<body>
    <div id="app">
        <nav class="white" role="navigation">
            <div class="nav-wrapper container">
                <a id="logo-container" href="{{ url('/') }}" class="brand-logo black-text">
                    {{ config('app.name', 'NaijaEdu-Pact') }}
                </a>
                <ul class="right hide-on-med-and-down">
                    @guest
                        @if (Route::has('login'))
                            <li><a href="{{ route('login') }}" class="black-text">{{ __('Login') }}</a></li>
                        @endif
                        @if (Route::has('register'))
                            <li><a href="{{ route('register') }}" class="btn-flat waves-effect waves-dark indigo-text text-darken-1" style="border: 1px solid #3f51b5;">{{ __('Register') }}</a></li>
                        @endif
                    @else
                        <li><a href="{{ route('projects.index') }}" class="black-text">Explore Projects</a></li>
                        <li><a href="{{ route('home') }}" class="btn waves-effect waves-light indigo">Dashboard</a></li>
                        <li>
                            <a class="black-text" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    @endguest
                </ul>

                <ul id="nav-mobile" class="sidenav">
                     @guest
                        @if (Route::has('login'))
                            <li><a href="{{ route('login') }}">{{ __('Login') }}</a></li>
                        @endif
                        @if (Route::has('register'))
                            <li><a href="{{ route('register') }}">{{ __('Register') }}</a></li>
                        @endif
                    @else
                        <li><a href="{{ route('projects.index') }}">Explore Projects</a></li>
                        <li><a href="{{ route('home') }}">Dashboard</a></li>
                        <li>
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form-mobile').submit();">
                                {{ __('Logout') }}
                            </a>
                            <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    @endguest
                </ul>
                <a href="#" data-target="nav-mobile" class="sidenav-trigger"><i class="material-icons black-text">menu</i></a>
            </div>
        </nav>

        <main class="py-4" style="flex-grow: 1;">
            @yield('content')
        </main>

        <footer class="page-footer indigo darken-3">
            <div class="container">
                <div class="row">
                    <div class="col l4 s12">
                        <h5 class="white-text">NaijaEdu-Pact</h5>
                        <p class="grey-text text-lighten-4">Connecting alumni with their alma maters for a better tomorrow.</p>
                    </div>
                    <div class="col l2 offset-l1 s6">
                        <h5 class="white-text">For Donors</h5>
                        <ul>
                            <li><a class="grey-text text-lighten-3" href="{{ route('projects.index') }}">Explore Projects</a></li>
                            <li><a class="grey-text text-lighten-3" href="{{ route('landing') }}#how-it-works">How It Works</a></li>
                            <li><a class="grey-text text-lighten-3" href="{{ route('login') }}">Login</a></li>
                        </ul>
                    </div>
                    <div class="col l2 s6">
                        <h5 class="white-text">For Universities</h5>
                        <ul>
                            <li><a class="grey-text text-lighten-3" href="#">Partner With Us</a></li>
                            <li><a class="grey-text text-lighten-3" href="{{ route('login') }}">Admin Login</a></li>
                        </ul>
                    </div>
                    <div class="col l3 s12">
                        <h5 class="white-text">Legal</h5>
                        <ul>
                            <li><a class="grey-text text-lighten-3" href="#">Privacy Policy</a></li>
                            <li><a class="grey-text text-lighten-3" href="#">Terms of Use</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="footer-copyright indigo darken-4">
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
            
            var modalElems = document.querySelectorAll('.modal');
            M.Modal.init(modalElems);
        });
    </script>
    @stack('scripts')
</body>
</html>
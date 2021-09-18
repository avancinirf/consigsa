<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Custom Styles -->
    <link href="{{ asset('css/custom_style.css') }}" rel="stylesheet">

</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container-fluid mx-5">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="/img/marca_horizontal_grande.png" alt="">
                    <!--{{ config('app.name', 'Laravel') }}-->
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        <!--@auth
                        @if ( auth()->user()->admin)
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">Gestão de dados</a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#">Projetos</a>
                                    <a class="dropdown-item" href="#">Arquivos</a>
                                    <a class="dropdown-item" href="#">Geometrias</a>
                                    <a class="dropdown-item" href="#">Imagens</a>
                                </div>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">Meus recursos</a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#">Projetos</a>
                                    <a class="dropdown-item" href="#">Cursos</a>
                                    <a class="dropdown-item" href="#">Arquivos</a>
                                    <a class="dropdown-item" href="#">Geometrias</a>
                                    <a class="dropdown-item" href="#">Imagens</a>
                                </div>
                            </li>
                        @endif
                        @endauth-->
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                            <a class="nav-link" href="/sobre">Sobre</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/servicos">Serviços</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/blog">Blog</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/contato">Fale conosco</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link pt-1" href="#"><i class="bi-twitter" style="font-size: 1.3rem;"></i></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link pt-1" href="#"><i class="bi-linkedin" style="font-size: 1.3rem;"></i></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link pt-1" href="#"><i class="bi-facebook" style="font-size: 1.3rem;"></i></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link pt-1" href="#"><i class="bi-instagram" style="font-size: 1.3rem;"></i></a>
                        </li>
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Entrar') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Registrar') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    @if (auth()->user()->admin)
                                        <a class="dropdown-item" href="#">Gestão de Dados</a>
                                        <a class="dropdown-item" href="app/projetos">Projetos</a>
                                        <a class="dropdown-item" href="#">Arquivos</a>
                                        <a class="dropdown-item" href="#">Geometrias</a>
                                        <a class="dropdown-item" href="#">Imagens</a>
                                    @else
                                        <a class="dropdown-item" href="#">Dados pessoais</a>
                                        <a class="dropdown-item" href="#">Projetos</a>
                                        <a class="dropdown-item" href="#">Mapas</a>
                                        <a class="dropdown-item" href="#">Cursos</a>
                                    @endif
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <!--<main class="py-4">-->
        <main class="py-0">
            @yield('content')
        </main>
    </div>
</body>
</html>

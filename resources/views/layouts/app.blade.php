<!doctype html>
<html class="h-100" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    {{-- <script src="{{ asset('js/app.js') }}" defer></script> --}}
    {{-- <script src="{{ asset('js/app.js?v='.filemtime(public_path('js/app.js'))) }}" defer></script> --}}
    {{-- @vite(['resources/css/app.css', 'resources/css/andrei.css', 'resources/js/app.js']) --}}
    @vite(['resources/js/app.js'])

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/andrei.css') }}" rel="stylesheet"> --}}

    <!-- Font Awesome links -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body class="d-flex flex-column h-100">
    @auth
    {{-- <div id="app"> --}}
    <header>
        <nav class="navbar navbar-lg navbar-expand-lg navbar-dark shadow culoare1"
            {{-- style="background-color: #2f5c8f" --}}
        >
            <div class="container">
                <a class="navbar-brand me-5" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        {{-- <li class="nav-item me-3">
                            <a class="nav-link active" aria-current="page" href="/programari">
                                <i class="fa-solid fa-calendar-check me-1"></i>Programări
                            </a>
                        </li> --}}
                        @if (auth()->user()->role === 'admin')
                            <li class="nav-item me-3 dropdown">
                                <a class="nav-link active dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-calendar-check me-1"></i>
                                    Programări
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li>
                                        <form class="needs-validation" novalidate method="GET" action="/programari">
                                            <input type="hidden" name="search_data" value="{{ \Carbon\Carbon::now()->todatestring() }}">
                                            <button class="dropdown-item btn btn-link" href="programari" type="submit">
                                                Azi
                                            </button>
                                        </form>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="/programari">
                                            Toate
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item me-3">
                                <a class="nav-link active" aria-current="page" href="/programari/afisare-calendar">
                                    <i class="fa-solid fa-calendar-days me-1"></i>Calendar
                                </a>
                            </li>
                            <li class="nav-item me-3">
                                <a class="nav-link active" aria-current="page" href="/pontaje">
                                    <i class="fa-solid fa-clock me-1"></i>
                                    Pontaje
                                </a>
                            </li>
                            {{-- <li class="nav-item me-3">
                                <a class="nav-link active" aria-current="page" href="/necesare">
                                    <i class="fa-solid fa-screwdriver-wrench me-1"></i>
                                    Necesare
                                </a>
                            </li> --}}
                            <li class="nav-item me-3 dropdown">
                                <a class="nav-link active dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-users me-1"></i>
                                    Mecanici
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li>
                                        <a class="dropdown-item" href="/concedii">
                                            <i class="fa-solid fa-users-slash me-1"></i>
                                            Concedii
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="/necesare">
                                            <i class="fa-solid fa-screwdriver-wrench me-1"></i>
                                            Necesare
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item me-3 dropdown">
                                <a class="nav-link active dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-bars me-1"></i>
                                    Utile
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li>
                                        <a class="dropdown-item" href="/manopere/export">
                                            Manopere export
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="/notificari">
                                            Notificări
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="/notificari/modificari-in-masa">
                                            Notificări - modificări în masă
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('mesaje-trimise-sms.index') }}">
                                            SMS trimise
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('zile-nelucratoare.index') }}">
                                            Zile nelucrătoare
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('clienti-neseriosi.index') }}">
                                            Clienți neserioși
                                        </a>
                                    </li>
                                    @if ((auth()->user()->name === "Andrei Dima") || (auth()->user()->name === "Viorel Admin"))
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('recenzii.index') }}">
                                                Recenzii
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="/recenzii/programari-excluse">
                                                Recenzii - Programări excluse
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @elseif (auth()->user()->role === 'mecanic')

                            @php
                                // dd(auth()->user()->manopere, auth()->user()->manopere->first()->programare);
                            @endphp
                            <li class="nav-item me-3">
                                <a class="nav-link active" aria-current="page" href="/mecanici/programari-mecanici">
                                    <i class="fa-solid fa-calendar-check me-1"></i>Programări
                                </a>
                            </li>
                            <li class="nav-item me-3">
                                <a class="nav-link active" aria-current="page" href="/mecanici/pontaje-mecanici/status">
                                    <i class="fa-solid fa-clock me-1"></i>Pontaje
                                </a>
                            </li>
                            {{-- <li class="nav-item me-3">
                                <a class="nav-link active" aria-current="page" href="/mecanici/bonusuri-mecanici">
                                    <i class="fa-solid fa-money-check-dollar me-1"></i>Bonusuri
                                </a>
                            </li> --}}
                            <li class="nav-item me-3">
                                <a class="nav-link active" aria-current="page" href="/necesare">
                                    <i class="fa-solid fa-screwdriver-wrench me-1"></i>
                                    Necesare
                                </a>
                            </li>
                            <li class="nav-item me-3">
                                <a class="nav-link active" aria-current="page" href="/mecanici/baza-de-date-programari">
                                    <i class="fa-solid fa-database me-1"></i>Baza de date Programări
                                </a>
                            </li>
                        @endif
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link active dropdown-toggle" href="#" id="navbarAuthentication" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ Auth::user()->name }}
                                </a>

                                <ul class="dropdown-menu" aria-labelledby="navbarAuthentication">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    @else
    {{-- <header style="min-height:86.5px; background-image: linear-gradient(#FFFFFF, #CDEFFF);"> --}}
    <header class="py-1 culoare1 d-flex justify-content-left" style="">
        <div class="container" style="display: inline-block">
                <img src="{{ asset('imagini/autogns-logo-01-2048x482.png') }}" class="bg-white"
                    style="width: auto; height: auto; max-width: 100%; max-height: 100px;">
        </div>
    </header>
    @endauth

    <main class="flex-shrink-0 py-4">
        @yield('content')
    </main>

    <footer class="mt-auto py-4 text-center text-white culoare1">
        <div class="">
            <p class="">
                © Auto GNS - Servicii Auto și Vulcanizare
            </p>
            <span class="text-white">
                <a href="https://validsoftware.ro/dezvoltare-aplicatii-web-personalizate/" class="text-white" target="_blank">
                    Aplicație web</a>
                dezvoltată de
                <a href="https://validsoftware.ro/" class="text-white" target="_blank">
                    validsoftware.ro
                </a>
            </span>
        </div>
    </footer>
</body>
</html>

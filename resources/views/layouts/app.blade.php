<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <script src="{{ asset('js/app.js') }}" defer></script>

        {{-- pusher --}}
        <script src="https://js.pusher.com/7.0/pusher.min.js" defer></script>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" />

        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    </head>
    <body>
        <div id="app">
            <nav class="navbar navbar-expand-lg navbar-dark px-5 py-3 topnavbar shadow">
                @auth
                    <a
                    class="navbar-brand font-weight-bold header text-uppercase"
                    href="{{
                        Auth()->user()->is_admin
                        ? route('admin.index_upload_user_file')
                        : route('user.user_files')
                    }}"
                    >
                        Birou Contabilitate
                    </a>
                @endauth
                @guest
                    <a
                    class="navbar-brand font-weight-bold header text-uppercase"
                    href="#!"
                    >
                        Birou Contabilitate
                    </a>
                @endguest
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto">
                        @if (Auth::check() && !Auth::user()->is_admin)
                            <li class="nav-item mr-3">
                                <span class="nav-link text-uppercase">
                                    <i class="fas fa-user"></i>
                                    {{ Auth()->user()->name }}
                                </span>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-uppercase" href="{{ route('user.user_files') }}">Urcare Fișiere</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-uppercase" href="{{ route('user.received_files') }}">Fișiere Primite</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-uppercase" href="{{ route('chat.message') }}">Mesaje</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-uppercase" href="{{ route('login.logout') }}">
                                    <i class="fas fa-sign-out-alt"></i>
                                    Deconectare
                                </a>
                            </li>
                        @elseif (Auth::check() && Auth::user()->is_admin)
                            <li class="nav-item mr-3">
                                <span class="nav-link text-uppercase">
                                    <i class="fas fa-user-cog"></i>
                                    {{ Auth()->user()->name }}
                                </span>
                            </li>
                            <li class="nav-item mr-3">
                                <a class="nav-link text-uppercase" href="{{ route('admin.index_upload_user_file') }}">
                                    Fișiere Clienți
                                </a>
                            </li>
                            <li class="nav-item mr-3">
                                <a class="nav-link text-uppercase" href="{{ route('admin.users') }}">Clienți</a>
                            </li>
                            <li class="nav-item mr-3">
                                <a class="nav-link text-uppercase" href="{{ route('admin.register') }}">Înregistrare Clienți</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-uppercase" href="{{ route('chat.message') }}">Mesaje</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-uppercase" href="{{ route('login.logout') }}">
                                    <i class="fas fa-sign-out-alt"></i>
                                    Deconectare
                                </a>
                            </li>
                        @else
                            <li class="nav-item mr-3">
                                <a class="nav-link text-uppercase" href="{{ route('login') }}">Conectare<span class="sr-only">(current)</span></a>
                            </li>
                        @endif
                    </ul>
                </div>
            </nav>

            <main class="py-5">
                @yield('content')
            </main>

            <footer class="footer shadow">
                <div class="footer-content">
                    <span class="footer-title">birou contabilitate</span>
                    <p class="footer-desc p-0 m-0">Toate drepturile rezervate | &copy; Copyright 2020</p>
                </div>
            </footer>
        </div>
    </body>
</html>

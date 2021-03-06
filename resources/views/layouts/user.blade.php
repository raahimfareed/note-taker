<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>@yield('title', env('APP_NAME'))</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin> 
    <link rel="stylesheet" href="{{ asset("css/app.css") }}">

    @yield('head')
</head>
<body class="overflow-hidden">
    <nav class="navbar navbar-expand-lg nav-dark navbar-dark d-print-none">
        <div class="container">
            <a href="{{ route('home') }}" class="navbar-brand">{{ env("APP_NAME") }}</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon text-light"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a href="{{ route('home') }}" class="nav-link @if (Request::is('home'))active @endif">Home</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link cursor-pointer dropdown-toggle" role="button", data-bs-toggle="dropdown">{{ auth()->user()->username }}</a>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a href="{{ route('settings') }}" class="dropdown-item @if (Request::is('settings') || Request::is('settings/*'))active @endif">Settings</a></li>
                            <hr>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="text-danger dropdown-item">Logout</button>
                                </form>
                            </li>
                            <li>
                                <h6 class="dropdown-header">&copy; {{ env('APP_NAME') }} 2021</h6>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        @yield("main")
    </main>

    <script src="{{ asset('js/app.js') }}"></script>
    @yield("scripts")
</body>
</html>
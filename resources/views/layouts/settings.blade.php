<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>@yield('title', env('APP_NAME'))</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset("css/app.css") }}">

    @yield('head')
</head>
<body id="settings">
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
                            <li><a href="{{ route('home') }}" class="dropdown-item @if (Request::is('settings') || Request::is('settings/*'))active @endif">Settings</a></li>
                            <hr>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="text-danger dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        <div class="container">
            <div class="row">
                <div class="d-none d-md-block col-md-4 col-lg-3 offset-lg-1">
                    <nav class="nav nav-pills flex-column sidebar">
                        <a href="{{ route('settings.profile') }}" class="nav-link @if(Request::is('settings/profile'))active @endif">Profile</a>
                        <a href="{{ route('settings.security') }}" class="nav-link @if(Request::is('settings/security'))active @endif">Security</a>
                        <a href="{{ route('settings.notes') }}" class="nav-link @if(Request::is('settings/notes'))active @endif">Notes</a>
                    </nav>
                </div>
                <div class="col-md-8 col-lg-6">
                    @yield("main")
                </div>
            </div>
        </div>
    </main>

    <footer class="footer py-2">
        <div class="container">
            <small>&copy; {{ env('APP_NAME') }} 2021</small>
            <small class="float-end"><a href="https://www.github.com/raahimfareed" target="_blank" class="text-light"><span class="fa fa-github"></span></a></small>
        </div>
    </footer>

    <script src="{{ asset('js/app.js') }}"></script>
    @yield("scripts")
</body>
</html>
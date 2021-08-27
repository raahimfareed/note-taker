<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>@yield('title', env('APP_NAME'))</title>

    <link rel="stylesheet" href="{{ asset("css/app.css") }}">

    @yield('head')
</head>
<body>
    <nav class="navbar navbar-expand-lg nav-dark navbar-dark">
        <div class="container">
            <a href="{{ route('home') }}" class="navbar-brand">{{ env("APP_NAME") }}</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon text-light"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a href="{{ route('home') }}" class="nav-link">Home</a></li>
                    <li class="nav-item"><a href="{{ route('home') }}" class="nav-link">Account</a></li>
                    <li class="nav-item"><a href="{{ route('home') }}" class="nav-link">Settings</a></li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm nav-link text-danger">Logout</button>
                        </form>
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
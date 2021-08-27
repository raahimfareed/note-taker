<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', env('APP_NAME', 'Welcome'))</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <nav class="navbar navbar-expand-lg nav-dark">
        <div class="container">
            <a href="{{ route('index') }}" class="navbar-brand">{{ env('APP_NAME', 'Notes') }}</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon text-light"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a href="{{ route('index') }}" class="nav-link @if(Route::is('index')) active @endif">Home</a></li>
                    <li class="nav-item"><a href="{{ route('login') }}" class="nav-link login-btn">Login</a></li>
                    <li class="nav-item"><a href="{{ route('register') }}" class="nav-link signup-btn @if(Route::is('register')) active @endif">Register</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        @yield("main")
    </main>

    <footer>
        <div class="container">
            <div class="row py-5 my-md-5 border-top">
                <div class="col-6 col-md-3">
                    <h4><a href="{{ route('index') }}" class="text-dark text-decoration-none">{{ env("APP_NAME") }}</a></h4>
                    <p class="text-muted">&copy; 2021</p>
                </div>
                <div class="col-6 col-md-3">
                    <h5>Section</h5>
                    <ul class="nav flex-column">
                        <li class="nav-item mb-2"><a href="" class="nav-link p-0 text-muted">Hello</a></li>
                        <li class="nav-item mb-2"><a href="" class="nav-link p-0 text-muted">Hello</a></li>
                        <li class="nav-item mb-2"><a href="" class="nav-link p-0 text-muted">Hello</a></li>
                        <li class="nav-item mb-2"><a href="" class="nav-link p-0 text-muted">Hello</a></li>
                        <li class="nav-item mb-2"><a href="" class="nav-link p-0 text-muted">Hello</a></li>
                    </ul>
                </div>
                <div class="col-6 col-md-3">
                    <h5>Section</h5>
                    <ul class="nav flex-column">
                        <li class="nav-item mb-2"><a href="" class="nav-link p-0 text-muted">Hello</a></li>
                        <li class="nav-item mb-2"><a href="" class="nav-link p-0 text-muted">Hello</a></li>
                        <li class="nav-item mb-2"><a href="" class="nav-link p-0 text-muted">Hello</a></li>
                        <li class="nav-item mb-2"><a href="" class="nav-link p-0 text-muted">Hello</a></li>
                        <li class="nav-item mb-2"><a href="" class="nav-link p-0 text-muted">Hello</a></li>
                    </ul>
                </div>
                <div class="col-6 col-md-3">
                    <h5>Section</h5>
                    <ul class="nav flex-column">
                        <li class="nav-item mb-2"><a href="" class="nav-link p-0 text-muted">Hello</a></li>
                        <li class="nav-item mb-2"><a href="" class="nav-link p-0 text-muted">Hello</a></li>
                        <li class="nav-item mb-2"><a href="" class="nav-link p-0 text-muted">Hello</a></li>
                        <li class="nav-item mb-2"><a href="" class="nav-link p-0 text-muted">Hello</a></li>
                        <li class="nav-item mb-2"><a href="" class="nav-link p-0 text-muted">Hello</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <script src="{{ asset("js/app.js") }}"></script>
</body>
</html>
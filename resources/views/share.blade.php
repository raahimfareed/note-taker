<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>{{ $note->title }} | {{ $user->username }} | {{ env('APP_NAME') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin> 
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono&display=swap" rel="stylesheet">
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
                    @auth
                    <li class="nav-item"><a href="{{ route('home') }}" class="nav-link">Home</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link cursor-pointer dropdown-toggle" role="button", data-bs-toggle="dropdown">{{ auth()->user()->username }}</a>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a href="{{ route('settings') }}" class="dropdown-item">Settings</a></li>
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
                    @endauth
                    @guest
                    <li class="nav-item"><a href="{{ route('index') }}" class="nav-link">Home</a></li>
                    <li class="nav-item"><a href="{{ route('login') }}" class="nav-link login-btn">Login</a></li>
                    <li class="nav-item"><a href="{{ route('register') }}" class="nav-link signup-btn">Register</a></li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main>
        <div class="page-loader">
            <div class="spinner-border my-2 me-3" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <section id="main-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="d-none d-md-block col-md-6 px-0 d-print-none">
                        <div id="editor"></div>
                    </div>
                    <div class="col-12 col-md-6 text-light py-3 px-4 d-print-block" id="markdown-preview">
                        <div class="title">
                            <input type="text" name="note-title" class="text-light w-100 mb-3 d-print-none" value="{{ $note->title }}" disabled autocomplete=off>
                            <span class="d-none d-print-block">{{ $note->title }}</span>
                        </div>
                        <div class="content pe-3"></div>
                    </div>
                </div>
            </div>
        </section>
        <div class="toast align-items-center text-white bg-primary border-0" role="alert" data-bs-autohide="false" data-toast-type="ongoing">
            <div class="d-flex justify-content-around">
                <div class="toast-body"></div>
                <div class="spinner-border my-2 me-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
        <div class="toast align-items-center text-white border-0" role="alert" data-toast-type="static">
            <div class="d-flex">
                <div class="toast-body"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
        <div id="context-menu" class="text-light">
            <div class="item share">
                <span class="fa fa-share-alt"></span> Share
            </div>
            <hr>
            <div class="item text-danger delete">
                <span class="fa fa-trash-o"></span> Delete
            </div>
        </div>
        <div class="modal fade" id="shareModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Share your note!</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="input-group mb-3">
                            <span class="input-group-text"><span class="fa fa-share-alt"></span></span>
                            <input type="text" class="form-control" id="shareLinkInput" placeholder="Copy Link" onclick="copyToClipboard()" readonly="readonly">
                            <span class="input-group-text cursor-pointer" onclick="copyToClipboard()"><i class="fa fa-files-o" aria-hidden="true"></i></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/remarkable/1.7.1/remarkable.min.js"></script>
    <script src="https://unpkg.com/monaco-editor@0.27.0/min/vs/loader.js"></script>
    <script>
        window.addEventListener("load", () => {
            document.querySelector(".page-loader").classList.add("d-none");
            document.querySelector("body").classList.remove("overflow-hidden");
        });

        // Monaco
        let editor = document.getElementById('editor');

        // markdown renderer
        let md = new Remarkable();
        // rendered markdown wrapper
        let markDownPreview = document.getElementById("markdown-preview");

        let readOnly = true;
        let wordWrap = "on";

        let body = `{!! $note->body !!}`;

        // init monaco editor
        require.config({ paths: { 'vs': 'https://unpkg.com/monaco-editor@0.27.0/min/vs' }});
        require(["vs/editor/editor.main"], () => {
            window.editor = monaco.editor.create(editor, {
                value: body,
                language: 'markdown',
                theme: 'vs-dark',
                readOnly: readOnly,
                wordWrap: wordWrap,
                // jetbrains mono cdn included in head separately
                fontFamily: "'JetBrains Mono', 'Droid Sans Mono', monospace, 'Droid Sans Fallback'",
                fontLigatures: true,
                renderWhitespace: "all",
                cursorBlinking: "smooth",
                cursorSmoothCaretAnimation: true,
            });

            // render startup content to view pane in html form
            markDownPreview.querySelector(".content").innerHTML = md.render(window.editor.getValue());



            document.addEventListener("keydown", async (event) => {
                // ALT + Z to toggle word wrap
                if (event.altKey && event.key === 'z') {
                    event.preventDefault();
                    wordWrap === "on"
                    ? wordWrap = "off"
                    : wordWrap = "on";

                    window.editor.updateOptions({ wordWrap: wordWrap });
                }
            });



            // call editor.layout() so that monaco editor resizes itself
            // after the browser is resized
            window.addEventListener("resize", (event) => {
                window.editor.layout();
            });
        });
    </script>
</body>
</html>
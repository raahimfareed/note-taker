@extends('layouts.user')

@section("head")
<meta name="csrf-token" content="{{ csrf_token() }}" />
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
@endsection

@section("main")
<section id="main-body">
    <div class="container-fluid">
        <div class="row">
            <div class="d-none d-md-block col-md-3 col-lg-2 text-light px-0 sidebar">
                <span class="text-light d-flex flex-row-reverse align-items-center justify-content-between flex-shrink-0 p-3">
                    <span class="fa fa-plus-circle cursor-pointer" title="Create New"></span>
                </span>
                <div class="list-group list-group-flush scrollarea">
                    <a href="#!" class="list-group-item list-group-item-action py-3 notes-list-item" data-token='a token'>
                        <div class="d-flex w-100 align-items-center justify-content-between">
                            <strong class="mb-1">Title</strong>
                            <small>Last Edited Date</small>
                        </div>
                        <div class="col-10 mb-1 small w-100 text-truncate">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Atque, ut.
                        </div>
                    </a>
                    <a href="#!" class="list-group-item list-group-item-action py-3 notes-list-item">
                        <div class="d-flex w-100 align-items-center justify-content-between">
                            <strong class="mb-1">Lorem ipsum dolor sit amet</strong>
                            <small>Last Edited Date</small>
                        </div>
                        <div class="col-10 mb-1 small w-100 text-truncate">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Atque, ut.
                        </div>
                    </a>
                    <a href="#!" class="list-group-item list-group-item-action py-3 notes-list-item">
                        <div class="d-flex w-100 align-items-center justify-content-between">
                            <strong class="mb-1">Title</strong>
                            <small>Last Edited Date</small>
                        </div>
                        <div class="col-10 mb-1 small w-100 text-truncate">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Atque, ut.
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-md-9 col-lg-5 px-0">
                <div id="editor"></div>
            </div>
            <div class="d-lg-block col-lg-5 text-light" id="markdown-preview"></div>
        </div>
    </div>
</section>
@endsection

@section("scripts")
<script src="https://cdn.jsdelivr.net/remarkable/1.7.1/remarkable.min.js"></script>
<script src="https://unpkg.com/monaco-editor@latest/min/vs/loader.js"></script>
<script>
    let md = new Remarkable();
    let starterContent = `Handy Dandy tips to get started :D\n\nPress CTRL + S to save\n\nPress ALT + N to create a new note\n\nPress ALT + Z to toggle word wrap\n\n[//]: # (This is a comment)\n\n# This is a heading\n\n*I am italic*\n**I am bold**\n***I am italic AND bold***\n\n\`This is a code line\`\n\n\`\`\`\nThis is\na really useful\nCode block\n\`\`\`\n\n[This is a link, more tips for markdown here](https://www.markdownguide.org/getting-started/)`;

    let wordWrap = "on";

    let markDownPreview = document.getElementById("markdown-preview");

    require.config({ paths: { 'vs': 'https://unpkg.com/monaco-editor@latest/min/vs' }});
    require(["vs/editor/editor.main"], () => {
        window.editor = monaco.editor.create(document.getElementById('editor'), {
            value: starterContent,
            language: 'markdown',
            theme: 'vs-dark',
            readOnly: true,
            wordWrap: wordWrap
        });

        markDownPreview.innerHTML = md.render(window.editor.getValue());


        document.addEventListener("keydown", async (event) => {
            if (event.ctrlKey && event.key === 's') {
                event.preventDefault();
                const token = document.head.querySelector('meta[name="csrf-token"]').getAttribute("content");
                let response = await fetch("/note/store", {
                    headers: {
                        "Content-Type": "Application/json",
                        "X-CSRF-TOKEN": token
                    },
                    method: "POST",
                    body: JSON.stringify({
                        "value": window.editor.getValue()
                    }),
                });

                if (response.status === 204) {
                    // Run code upon successfully creating post
                    console.log("Created and Saved");
                }

            }

            if (event.altKey && event.key === 'z') {
                event.preventDefault();
                wordWrap === "on"
                ? wordWrap = "off"
                : wordWrap = "on";

                editor.updateOptions({ wordWrap: wordWrap });
            }
            
            if (event.ctrlKey && event.key === 'q') {
                event.preventDefault();
                console.log("Reset Editor");
            }

            if (event.altKey && event.key === 'n') {
                event.preventDefault();
                console.log("New Note");
            }
        });

        window.addEventListener("resize", (event) => {
            window.editor.layout();
        });

        window.editor.onDidChangeModelContent((event) => {
            markDownPreview.innerHTML = md.render(window.editor.getValue());
        });
    });

</script>
@endsection

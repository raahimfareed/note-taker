@extends('layouts.user')

@section("head")
<meta name="csrf-token" content="{{ csrf_token() }}" />
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono&display=swap" rel="stylesheet"> 
@endsection

@section("main")
<div class="page-loader">
    <div class="spinner-border my-2 me-3" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>
<section id="main-body">
    <div class="container-fluid">
        <div class="row">
            <div class="d-none d-md-block col-md-3 col-lg-2 text-light px-0 sidebar">
                <span class="text-light d-flex flex-row-reverse align-items-center justify-content-between flex-shrink-0 p-3">
                    <div class="dropdown w-100" id="newNoteDropdown">
                        <span class="fa fa-plus-circle cursor-pointer dropdown-toggle" id="newNoteButton" data-bs-toggle="dropdown" title="Create New"></span>
                        <form method="POST" class="dropdown-menu px-3 w-100" id="newNoteForm">
                            <div class="mb-2 title-container">
                                <label for="formLabelTitle" class="form-label">Title</label>
                                <input type="text" name="title" id="formNoteTitle" class="form-control" />
                            </div>
                            <button type="submit" class="btn btn-sm float-end bg-primary text-light">Create</button>
                        </form>
                    </div>
                </span>
                <div class="list-group list-group-flush scrollarea" id="notesCollection">
                    @if ($notes->count())
                        @foreach ($notes as $note)
                            <x-note-link :note="$note" />
                        @endforeach
                    @else
                        <p class="mx-auto text-center">Make a new note to get started ^-^</p>
                    @endif
                </div>
            </div>
            <div class="col-md-9 col-lg-5 px-0 d-print-none">
                <div id="editor"></div>
            </div>
            <div class="d-none d-lg-block col-lg-5 text-light py-3 px-4 d-print-block" id="markdown-preview">
                <div class="title">
                    <input type="text" name="note-title" class="text-light w-100 mb-3 d-print-none" value="Welcome" autocomplete=off>
                    <span class="d-none d-print-block">Welcome</span>
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
    <div class="item">
        <span class="fa fa-share-alt"></span> Share
    </div>
    <hr>
    <div class="item text-danger delete">
        <span class="fa fa-trash-o"></span> Delete
    </div>
</div>
@endsection

@section("scripts")
<script src="https://cdn.jsdelivr.net/remarkable/1.7.1/remarkable.min.js"></script>
<script src="https://unpkg.com/monaco-editor@0.27.0/min/vs/loader.js"></script>
<script>
    window.addEventListener("load", () => {
        document.querySelector(".page-loader").classList.add("d-none");
        document.querySelector("body").classList.remove("overflow-hidden");
    });

    // global vars
    // monaco editor element wrapper
    let editor = document.getElementById('editor');

    // markdown renderer
    let md = new Remarkable();

    // starting content to be displayed in editor
    let starterContent = `Handy Dandy tips to get started :D\n\nPress CTRL + S to save\n\nPress ALT + N to create a new note\n\nPress ALT + Z to toggle word wrap\n\n[//]: # (This is a comment)\n\n# This is a heading\n\n*I am italic*\n**I am bold**\n***I am italic AND bold***\n\n[//]: # (This is a comment, it doesn't show inside the final note)\n\n\`This is a code line\`\n\n\`\`\`\nThis is\na really useful\nCode block\n\`\`\`\n\n[This is a link, more tips for markdown here](https://www.markdownguide.org/getting-started/)\n\n[![Raahim Fareed](https://c5.patreon.com/external/favicon/favicon-32x32.png?v=69kMELnXkB "Patreon") Consider supporting me](https://www.patreon.com/raahimfareed "Raahim Fareed")`;

    // editor option to wrap the words
    let wordWrap = "on";

    // editor read only
    let readOnly = true;

    // rendered markdown wrapper
    let markDownPreview = document.getElementById("markdown-preview");

    // new note elements
    let newNoteButton = document.getElementById("newNoteButton");
    let newNoteDropdown = document.getElementById("newNoteDropdown");
    let newNoteForm = document.getElementById("newNoteForm");
    let newNoteTitle = document.getElementById("formNoteTitle");

    // all notes sidebar
    let notesCollection = document.getElementById("notesCollection");

    // ctx menu
    let contextMenu = document.getElementById("context-menu");



    // don't close the dropdown right away upon submitting the form
    newNoteDropdown.addEventListener('click', (event) => {
        event.stopPropagation();
    });



    // init monaco editor
    require.config({ paths: { 'vs': 'https://unpkg.com/monaco-editor@0.27.0/min/vs' }});
    require(["vs/editor/editor.main"], () => {
        window.editor = monaco.editor.create(editor, {
            // config for monaco
            value: starterContent,
            language: 'markdown',
            theme: 'vs-dark',
            readOnly: readOnly,
            wordWrap: wordWrap,
            // jetbrains mono cdn included in head separately
            fontFamily: "'JetBrains Mono', 'Droid Sans Mono', monospace, 'Droid Sans Fallback'",
            fontLigatures: true,
            //renderControlCharacters: true, // No affect on editor
            renderWhitespace: "all",
            cursorBlinking: "smooth",
            cursorSmoothCaretAnimation: true,
        });

        // place to store current note's token
        window.editor.note_token = null

        // render startup content to view pane in html form
        markDownPreview.querySelector(".content").innerHTML = md.render(window.editor.getValue());
        markDownPreview.querySelector(".title").querySelector("input").disabled = true;



        // upon keydown (mostly for shortcuts, autosave implement soon)
        document.addEventListener("keydown", async (event) => {
            // TODO: Add title input to save and make it print friendly
            // CTRL + S to save the note
            if (event.ctrlKey && event.key === 's') {
                event.preventDefault();

                let noteLink = document.querySelector(`.notes-list-item[data-token="${window.editor.note_token}"]`);
                // if (noteLink && noteLink.classList.contains("delete")) return;
                if (readOnly) return;

                markDownPreview.querySelector(".title input").classList.remove("border", "border-danger");

                if (!window.editor.note_token) return;

                if (!markDownPreview.querySelector(".title input").value) {
                    markDownPreview.querySelector(".title input").classList.add("border", "border-danger");
                    let toastEl = document.querySelector(".toast[data-toast-type='static']");
                    toastEl
                        .querySelector(".toast-body")
                        .textContent = "Title cannot be empty";
                    toastEl.classList.add("bg-danger");
                    let toast = window.Bootstrap.Toast.getInstance(toastEl);
                    toast.show();
                    return;
                }

                if (markDownPreview.querySelector(".title input").value.length > 128) {
                    markDownPreview.querySelector(".title input").classList.add("border", "border-danger");
                    let toastEl = document.querySelector(".toast[data-toast-type='static']");
                    toastEl
                        .querySelector(".toast-body")
                        .textContent = "Title cannot be longer than 128";
                    if (toastEl.classList.contains("bg-success")) {
                        toastEl.classList.remove("bg-success");
                    }
                    toastEl.classList.add("bg-danger");
                    let toast = window.Bootstrap.Toast.getInstance(toastEl);
                    toast.show();
                    return;
                }

                // get toast with spinner and show it to user
                let toastEl = document.querySelector(".toast[data-toast-type='ongoing']");
                toastEl
                    .querySelector(".toast-body")
                    .textContent = "Saving Note";
                let toast = window.Bootstrap.Toast.getInstance(toastEl);
                toast.show();

                const token = document.head.querySelector('meta[name="csrf-token"]').getAttribute("content");
                let response = await fetch("{{ route('note.update') }}", {
                    headers: {
                        "Content-Type": "Application/json",
                        "X-CSRF-TOKEN": token
                    },
                    method: "POST",
                    body: JSON.stringify({
                        "title": markDownPreview.querySelector(".title input").value,
                        "body": window.editor.getValue(),
                        "note_token": window.editor.note_token
                    }),
                });

                if (response.status === 400) {
                    let toastEl = document.querySelector(".toast[data-toast-type='static']");
                    toastEl
                        .querySelector(".toast-body")
                        .textContent = "Title cannot be empty";
                    if (toastEl.classList.contains("bg-success")) {
                        toastEl.classList.remove("bg-success");
                    }
                    toastEl.classList.add("bg-danger");
                    let toast = window.Bootstrap.Toast.getInstance(toastEl);
                    toast.show();
                    return;
                }

                if (response.status === 200) {
                    // Run code upon successfully creating post
                    let result = await response.json();
                    let noteLink = document.querySelector(`.notes-list-item[data-token="${window.editor.note_token}"]`);

                    let noteDate = noteLink.querySelector(".updated-at");
                    while (noteDate.firstChild) {
                        noteDate.removeChild(noteDate.firstChild)
                    }
                    noteDate.appendChild(document.createTextNode(result.updated_at));

                    let noteBody = noteLink.querySelector(".body");
                    while (noteBody.firstChild) {
                        noteBody.removeChild(noteBody.firstChild)
                    }
                    noteBody.appendChild(document.createTextNode(result.body ? result.body : "I'm empty 😐"))

                    let noteTitle = noteLink.querySelector(".title");
                    noteTitle.textContent = result.title;

                    markDownPreview.querySelector(".title span").textContent = markDownPreview.querySelector(".title input").value
                }
                toast.hide();
            }

            // ALT + Z to toggle word wrap
            if (event.altKey && event.key === 'z') {
                event.preventDefault();
                wordWrap === "on"
                ? wordWrap = "off"
                : wordWrap = "on";

                window.editor.updateOptions({ wordWrap: wordWrap });
            }

            // CTRL + Q to close current note (will be implemented in future)
            if (event.ctrlKey && event.key === 'q') {
                event.preventDefault();
                console.log("Reset Editor");
            }

            // ALT + N to trigger new note dropdown
            if (event.altKey && event.key === 'n') {
                event.preventDefault();
                newNoteForm.classList.toggle("show");
                newNoteButton.classList.toggle("show");
                if (newNoteForm.classList.contains("show")) {
                    newNoteTitle.focus();
                }
            }
        });



        // call editor.layout() so that monaco editor resizes itself
        // after the browser is resized
        window.addEventListener("resize", (event) => {
            window.editor.layout();
        });



        // render markdown to view pane as the user types
        window.editor.onDidChangeModelContent((event) => {
            markDownPreview.querySelector(".content").innerHTML = md.render(window.editor.getValue());
            if (markDownPreview.querySelector(".title").querySelector("input").disabled) {
                markDownPreview.querySelector(".title").querySelector("input").disabled = false;
            }

            // TODO: Makes the editor pretty slow, no syntax highlighting
            // in generated note
            // let codeBlocks = markDownPreview.querySelector(".content").querySelectorAll("code");
            // codeBlocks.forEach(codeBlock => {
            //     monaco.editor.colorize(codeBlock.innerHTML, "javascript")
            //         .then(html => codeBlock.innerHTML = html);
            // });
        });
    });



    // upon submitting new note form
    newNoteForm.addEventListener("submit", async (event) => {
        event.preventDefault();

        // make the editor and the submit button disabled so that the user
        // doesn't accidentally make multiple requests
        readOnly = true;
        window.editor.updateOptions({ readOnly: readOnly });
        newNoteForm.querySelector("button[type=submit]").disabled = true;

        // title input container in new note form
        let titleContainer = document.querySelector('.title-container');



        // if title is empty, show the user error and return
        if (!newNoteTitle.value || newNoteTitle.value === undefined || newNoteTitle.value === null || newNoteTitle.value === "") {
            if (!newNoteTitle.classList.contains("border-danger")) {
                newNoteTitle.classList.add("border-danger");

                let errorMsg = document.createElement("small");
                errorMsg.classList.add("text-danger", "smaller");
                errorMsg.innerText = "Required";

                titleContainer.appendChild(errorMsg);
            }

            // enable editor if it has a note in it, and enable the button
            newNoteForm.querySelector("button[type=submit]").disabled = false;
            readOnly = false;
            if (window.editor.note_token) window.editor.updateOptions({ readOnly: readOnly });
            return;
        }



        // make sure the title is not greater than 128 characters long,
        // also checked server side
        if (newNoteTitle.value.length > 128) {
            newNoteTitle.classList.add("border-danger");

            let errorMsg = document.createElement("small");
            errorMsg.classList.add("text-danger", "smaller");
            errorMsg.innerText = `Your title can only be 128 characters long.`;

            titleContainer.appendChild(errorMsg);

            // enable editor if it has a note in it, and enable the button
            newNoteForm.querySelector("button[type=submit]").disabled = false;
            readOnly = false;
            if (window.editor.note_token) window.editor.updateOptions({ readOnly: readOnly });
            return;
        }



        // if no errors, and an error is displayed, remove it
        if (newNoteTitle.classList.contains("border-danger")) {
            newNoteTitle.classList.remove("border-danger");
            titleContainer.removeChild(titleContainer.querySelector("small"));
        }



        // get toast with spinner and show it to user
        let toastEl = document.querySelector(".toast[data-toast-type='ongoing']");
        toastEl
            .querySelector(".toast-body")
            .textContent = "Creating New Note";
        let toast = window.Bootstrap.Toast.getInstance(toastEl);
        toast.show();

        // csrf token stored in meta taf in head
        const token = document.head.querySelector('meta[name="csrf-token"]').getAttribute("content");



        // fetch request to make a new note
        let response = await fetch("{{ route('note.new') }}", {
            method: "POST",
            headers: {
                    "Content-Type": "Application/json",
                    "X-CSRF-TOKEN": token
                },
            body: JSON.stringify({
                title: newNoteTitle.value,
            })
        });



        // validation error
        if (response.status === 400) {
            let result = await response.json();

            if (result.title) {
                newNoteTitle.classList.add("border-danger");
                result.title.forEach(err => {
                    let errorMsg = document.createElement("small");
                    errorMsg.classList.add("text-danger", "smaller");
                    errorMsg.innerText = `Your title can only be 128 characters long.`;

                    titleContainer.appendChild(errorMsg);
                });
            }

            readOnly = false;
            if (window.editor.note_token) window.editor.updateOptions({ readOnly: readOnly });
            newNoteForm.querySelector("button[type=submit]").disabled = false;
            return;
        }



        // success
        if (response.status === 200) {
            let result = await response.json();

            // oncontextmenu="rightClick(this, event)" onclick="loadNote(this)"
            // create new note's sidebar link
            let noteCard = document.createElement("a");
            noteCard.classList.add("list-group-item","list-group-item-action", "py-3", "notes-list-item", "cursor-pointer", "active");
            document.querySelectorAll(`.notes-list-item.active`).forEach(element => {
                element.classList.remove("active");
            });
            noteCard.setAttribute("data-token", result.token);
            noteCard.setAttribute("oncontextmenu", "rightClick(this, event)");
            noteCard.setAttribute("onclick", "loadNote(this)");

            let noteCardTopBar = document.createElement("div");
            noteCardTopBar.classList.add("d-flex", "w-100", "align-items-center", "justify-content-between");
            
            let noteCardTitle = document.createElement("strong");
            noteCardTitle.classList.add("mb-1", "text-truncate", "title");
            noteCardTitle.appendChild(document.createTextNode(newNoteTitle.value));

            let noteCardDate = document.createElement("small");
            noteCardDate.classList.add("smaller", "updated-at");
            noteCardDate.appendChild(document.createTextNode(result.updated_at));

            noteCardTopBar.appendChild(noteCardTitle);
            noteCardTopBar.appendChild(noteCardDate);

            let noteCardDesc = document.createElement("div");
            noteCardDesc.classList.add("col-10", "mb-1", "small", "w-100", "text-truncate", "body");
            let emptyMsg = document.createElement("em");
            emptyMsg.appendChild(document.createTextNode("Such emptiness U-U"));
            noteCardDesc.appendChild(emptyMsg);

            noteCard.appendChild(noteCardTopBar);
            noteCard.appendChild(noteCardDesc);
            notesCollection.insertBefore(noteCard, notesCollection.firstChild);

            window.editor.note_token = result.token;
            window.editor.setValue("");
            markDownPreview.querySelector(".title input").value = newNoteTitle.value;
        }



        // hide spinner toast
        toast.hide();

        // clear its body
        toastEl
            .querySelector(".toast-body")
            .textContent = "";

        // make the editor editable again
        readOnly = false;
        window.editor.updateOptions({ readOnly: readOnly });

        // reset new note form
        newNoteForm.classList.remove("show");
        newNoteButton.classList.remove("show");
        newNoteTitle.value = null;
        newNoteForm.querySelector("button[type=submit]").disabled = false;
    });



    window.onblur = () => {
        closeCtxMenu();
    };



    document.onmousedown = () => {
        closeCtxMenu();
    };



    // contextMenu.onmousedown = (e) => {
    //     e.preventDefault();
    // };



    // To delete the note
    // TODO: Show a confirmation model to the user before deleting
    contextMenu.querySelector(".delete").onmousedown = async (e) => {
        readOnly = true;
        window.editor.updateOptions({ readOnly: readOnly });
        markDownPreview.querySelector(".title input").disabled = readOnly;
        const token = document.head.querySelector('meta[name="csrf-token"]').getAttribute("content");
        let note_token = contextMenu.dataset.token;
        let response = await fetch("{{ route('note.delete') }}", {
            method: "POST",
            headers: {
                "Content-Type": "Application/json",
                "X-CSRF-TOKEN": token
            },
            body: JSON.stringify({
                "_method": "DELETE",
                "note_token": note_token
            })
        });

        if (response.status === 204) {
            let noteLink = document.querySelector(`.notes-list-item[data-token="${note_token}"]`)
            noteLink.classList.add("delete");

            let deleted = false;

            noteLink.addEventListener("transitionend", () => {
                if (noteLink.classList.contains("hidden") && !deleted) {
                    deleted = true;
                    noteLink.parentElement.removeChild(noteLink);
                    return;
                }

                noteLink.classList.add("hidden");
            });

            let toastEl = document.querySelector(".toast[data-toast-type='static']");
            toastEl
                .querySelector(".toast-body")
                .textContent = "Note Deleted";
            if (toastEl.classList.contains("bg-danger")) {
                toastEl.classList.remove("bg-danger");
            }
            toastEl.classList.add("bg-success");
            let toast = window.Bootstrap.Toast.getInstance(toastEl);
            toast.show();

            

            // FIXME: Smooth delete animation not working properly
            // let addHidden = setTimeout(() => {
            //     noteLink.classList.add("hidden");
            // }, 250);
            //
            // setTimeout(() => {
            //     noteLink.parentElement.removeChild(noteLink);
            // }, 1000);

            return;
        }

        if (window.editor.note_token === token) {
            readOnly = false;
            markDownPreview.querySelector(".title input").disabled = readOnly;
            window.editor.updateOptions({ readOnly: readOnly });
        }
    }


    contextMenu.oncontextmenu = (e) => {
        e.preventDefault();
    };



    async function loadNote(self) {
        if (self.classList.contains("delete")) return;

        if (window.editor) {
            readOnly = true;
            window.editor.updateOptions({ readOnly: readOnly });
            let toastEl = document.querySelector(".toast[data-toast-type='ongoing']");
            toastEl
                .querySelector(".toast-body")
                .textContent = "Opening Note";
            let toast = window.Bootstrap.Toast.getInstance(toastEl);
            toast.show();

            let noteToken = self.dataset.token;
            const token = document.head.querySelector('meta[name="csrf-token"]').getAttribute("content");
            let response = await fetch("{{ route('note.get') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "Application/json",
                    "X-CSRF-TOKEN": token
                },
                body: JSON.stringify({
                    "note_token": noteToken
                })
            });

            // TODO: Need to add an error toast so the user is not confused
            // If the note is not found
            if (response.status === 404) {
                // Show an error toast to user
                let toastEl = document.querySelector(".toast[data-toast-type='static']");
                toastEl
                    .querySelector(".toast-body")
                    .textContent = "Note not found";
                if (toastEl.classList.contains("bg-success")) {
                    toastEl.classList.remove("bg-success");
                }
                toastEl.classList.add("bg-danger");
                let toast = window.Bootstrap.Toast.getInstance(toastEl);
                toast.show();
                return;
            }
            
            // TODO: Need to add an error toast so the user is not confused
            // If the user is logged in but session is expired
            if (response.status === 419) {
                // Show an error toast to user
                let toastEl = document.querySelector(".toast[data-toast-type='static']");
                toastEl
                    .querySelector(".toast-body")
                    .textContent = "Please refresh your page and try again";
                if (toastEl.classList.contains("bg-success")) {
                    toastEl.classList.remove("bg-success");
                }
                toastEl.classList.add("bg-danger");
                let toast = window.Bootstrap.Toast.getInstance(toastEl);
                toast.show();
                return;
            }

            if (response.status === 200) {
                let result = await response.json();

                if (result.note_token !== noteToken) {
                    console.log("An error occurred!\nNote Tokens do not match. Please refresh page and try again");

                    // Show an error toast to user
                    let toastEl = document.querySelector(".toast[data-toast-type='static']");
                    toastEl
                        .querySelector(".toast-body")
                        .textContent = "Please refresh your page and try again (Protip: you can press CTRL + R to refresh your page)";
                    if (toastEl.classList.contains("bg-success")) {
                        toastEl.classList.remove("bg-success");
                    }
                    toastEl.classList.add("bg-danger");
                    let toast = window.Bootstrap.Toast.getInstance(toastEl);
                    toast.show();
                    return;
                }

                if (window.editor.note_token) {
                    document.querySelectorAll(`.notes-list-item.active`).forEach(element => {
                        element.classList.remove("active");
                    });
                }
                self.classList.add("active");
                window.editor.setValue(result.body === null
                    ? ""
                    : result.body);
                window.editor.note_token = noteToken;
                markDownPreview.querySelector(".title").querySelector("input").disabled = false;
                markDownPreview.querySelector(".title").querySelector("input").value = result.title;
                markDownPreview.querySelector(".title span").textContent = result.title;
                readOnly = false;
                window.editor.updateOptions({ readOnly: readOnly });
                toast.hide();
            }
        }
    }



    function rightClick(self, event) {
        event.preventDefault();

        if (self.classList.contains("delete")) return;

        let topOffset = 0;

        if (contextMenu.offsetHeight + event.clientY > window.innerHeight) {
            topOffset = event.clientY - contextMenu.offsetHeight;
        } else {
            topOffset = event.clientY;
        }

        contextMenu.style.top = topOffset + "px";
        contextMenu.style.left = event.clientX + "px";

        contextMenu.dataset.token = self.dataset.token;

        contextMenu.classList.add("active");
    }



    function closeCtxMenu() {
        contextMenu.dataset.token = null;
        contextMenu.classList.remove("active");
    }
</script>
@endsection

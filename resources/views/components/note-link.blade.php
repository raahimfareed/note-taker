@props(['note' => $note])

<a class="list-group-item list-group-item-action notes-list-item cursor-pointer" data-token='{{ Crypt::encrypt($note->id); }}' oncontextmenu="rightClick(this, event)" onclick="loadNote(this)">
    <div class="d-flex w-100 align-items-center justify-content-between">
        <strong class="mb-1 text-truncate title">{{ $note->title }}</strong>
        <small class="smaller updated-at">{{ $note->updated_at->diffForHumans() }}</small>
    </div>
    <div class="col-10 mb-1 small w-100 text-truncate body">
        @if ($note->body)
            {{ Str::limit($note->body, $limit=150, $end="...") }}
        @else
            <em>Such emptiness U-U</em>
        @endif
    </div>
</a>
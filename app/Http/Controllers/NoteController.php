<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use SebastianBergmann\Environment\Console;

class NoteController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth"]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->only("title"), [
            "title" => ["required", "max:128"]
        ]);

        if ($validator->fails())
        {
            $errors = $validator->errors()->messages();

            return response()->json($errors, 400);
        }

        $id = $request->user()->notes()->create([
            "title" => $request->title
        ])->id;

        $note_date = Note::find($id)->updated_at->diffForHumans();

        return response()->json(["token" => Crypt::encrypt($id), "updated_at" => $note_date], 200);
    }

    public function update(Request $request)
    {
        $note = Note::find(Crypt::decrypt($request->note_token));
        $this->authorize("owner", $note);

        $validator = Validator::make($request->only("title"), [
            "title" => ["required", "required", "max:128"]
        ]);

        if ($validator->fails())
        {
            $errors = $validator->errors()->messages();

            return response()->json($errors, 400);
        }

        $note->title = $request->title;
        $note->body = $request->body;
        $note->save();
        // 204 means success with no response needed
        // 200 means success with a response
        return response()->json(["updated_at" => $note->updated_at->diffForHumans(), "body" => Str::limit($note->body, 128, '...'), "title" => $note->title], 200);
    }

    public function get(Request $request)
    {
        // $note = Note::find(Crypt::decrypt($request->note_token));
        $note = $request->user()->notes()->find(Crypt::decrypt($request->note_token));
        if ($note === null)
        {
            return response()->json([], 404);
        }

        return response()->json(["title" => $note->title, "body" => $note->body, "note_token" => $request->note_token], 200);
    }

    public function get_shared(Request $request)
    {
        // $note = Note::find(Crypt::decrypt($request->note_token));
        $note = $request->user()->notes()->find(Crypt::decrypt($request->note_token));
        if ($note === null)
        {
            return response()->json([], 404);
        }

        return response()->json(["title" => $note->title, "body" => $note->body, "note_token" => $request->note_token], 200);
    }

    public function destroy(Request $request)
    {
        $note = $request->user()->notes()->find(Crypt::decrypt($request->note_token));

        if (!$note)
        {
            return response()->json([], 404);
        }

        $note->delete();
        return response()->json([], 204);
    }

    public function share(Request $request)
    {
        $note = $request->user()->notes()->find(Crypt::decrypt($request->note_token));
        if ($note === null)
        {
            return response()->json([], 404);
        }

        if ($note->share_token === null)
        {
            $note->share_token = Str::uuid()->getHex();
            $note->save();
        }

        return response()->json(["link" => route("note.share.index", [auth()->user()->username, $note])], 200);
    }
}

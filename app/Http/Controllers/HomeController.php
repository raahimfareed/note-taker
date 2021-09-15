<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth"]);
    }

    public function index()
    {
        $notes = Auth::user()->notes()->orderBy("updated_at", "DESC")->with(['user'])->paginate(20);
        return view("index", [
            "notes" => $notes
        ]);
    }

    public function share(User $user, Note $note)
    {
        if ($user->username === $note->user->username)
        {
            $note->body = str_replace('`', '\`', $note->body);
            $note->body = str_replace(array("\r\n", "\n", "\r"), '\\n', $note->body);
            // dd($note->body);
            return view("share", ["user" => $user, "note" => $note]);
        }

        abort(404);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth"]);
    }

    public function store(Request $request)
    {
        // For creating a new post
    }

    public function update()
    {
        // 204 means no response body needed
        return response()->json([], 204);
    }
}

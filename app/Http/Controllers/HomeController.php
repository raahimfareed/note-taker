<?php

namespace App\Http\Controllers;

use App\Models\Note;
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
}

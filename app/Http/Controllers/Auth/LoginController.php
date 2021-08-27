<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware(["guest"]);
    }

    public function index()
    {
        return view("auth.login");
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            "username" => ["required", "max:255"],
            "password" => ["required"],
        ]);

        if (!Auth::attempt($request->only("username", "password"), $request->remember))
        {
            return back()->withErrors(["status" => "Invalid Login Details"]);
        }

        return redirect()->route("home");
    }
}

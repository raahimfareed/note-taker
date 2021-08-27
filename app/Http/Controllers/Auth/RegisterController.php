<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware(["guest"]);
    }

    public function index()
    {
        return view("auth.register");
    }

    public function store(Request $request)
    {
        // dd($request);
        $this->validate($request, [
            "name" => ["nullable", "max:255"],
            "username" => ["required", "max:255", "unique:users"],
            "email" => ["required", "email", "max:255", "unique:users"],
            "password" => ["required", "confirmed", Password::min(8)->mixedCase()->numbers()]
        ]);

        if (!isset($request->name) || $request->name === null || $request->name == '')
        {
            $request->name = $request->username;
        }

        User::create([
            "name" => $request->name,
            "username" => $request->username,
            "email" => $request->email,
            "password" => Hash::make($request->password)
        ]);

        Auth::attempt($request->only("email", "password"));

        return redirect()->route("home");
    }
}

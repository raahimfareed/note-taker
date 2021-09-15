<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPassword extends Controller
{
    public function __construct()
    {
        $this->middleware(["guest"]);
    }

    public function index()
    {
        return view("auth.forgot-password");
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            "email" => ["required", "email"]
        ]);

        $status = Password::sendResetLink(
            $request->only("email")
        );

        $request->session()->flash("email", $request->email);

        return $status === Password::RESET_LINK_SENT
            ? back()->with(["status" => __($status)])
            : back()->withErrors(["email" => __($status)]);
    }
}

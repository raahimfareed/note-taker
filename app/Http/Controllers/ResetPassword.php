<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password as PasswordValidate;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ResetPassword extends Controller
{
    public function __construct()
    {
        $this->middleware(["guest"]);
    }

    public function index($token)
    {
        return view("auth.reset-password", ["token" => $token]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            "token" => "required",
            "email" => ["required", "email"],
            "password" => ["required", "confirmed", PasswordValidate::min(8)->letters()->numbers()]
        ]);

        $status = Password::reset(
            $request->only("email", "password", "password_confirmation", "token"),
            function ($user, $password)
            {
                $user->forceFill([
                    "password" => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );


        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}

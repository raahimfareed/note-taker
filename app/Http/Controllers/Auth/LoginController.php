<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware(["guest"]);
    }

    public function index(Request $request)
    {
        $request->session()->regenerate();
        return view("auth.login");
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            "username" => ["required", "max:255"],
            "password" => ["required"],
        ]);

        $user = User::where("username", "=", $request->username)->first();

        if ($user === null)
        {
            return back()->withErrors(["status" => "User does not exist"]);
        }

        if (!$user->has2Fa())
        {
            if (!Auth::attempt($request->only("username", "password"), $request->remember))
            {
                return back()->withErrors(["status" => "Invalid Login Details"]);
            }

            $request->session()->regenerate();
            return redirect()->route("home");
        }

        $data = $request->all();

        $data["secret"] = $user->otp_secret;

        $request->session()->flash("data", $data);

        return redirect()->route("login.2fa");
    }
    
    public function index2Fa(Request $request)
    {
        if (is_null(session("data")))
        {
            return redirect()->route("login");
        }

        $request->session()->reflash();

        return view("auth.2fa_login");
    }

    public function store2Fa(Request $request)
    {
        if (is_null(session("data")))
        {
            return redirect()->route("login");
        }

        $request->merge(session("data"));
        $google2fa = (new \PragmaRX\Google2FAQRCode\Google2FA());
        // dd($request->secret);
        if ($google2fa->verifyKey(Crypt::decrypt($request->secret), $request->otp))
        {
            if (Auth::attempt($request->only("username", "password"), $request->remember))
            {
                $request->session()->regenerate();
                return redirect()->route("home");
            }

            return redirect()->route("login")->withErrors(["status" => "An error occurred, please try again later"]);
        }

        $request->session()->reflash();

        return back()->withErrors(["status" => "Invalid OTP"]);
    }
}

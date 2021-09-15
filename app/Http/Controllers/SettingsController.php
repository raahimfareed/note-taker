<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\MatchOldPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth"]);
    }

    public function index()
    {
        return redirect()->route("settings.profile");
    }

    public function profile()
    {
        return view("settings.profile");
    }

    public function updateProfile(Request $request)
    {
        $this->validate($request, [
            "name" => ["nullable", "max:255"],
            "username" => ["required", "max:255"],
            "email" => ["required", "email", "max:255"]
        ]);

        if (!isset($request->name) || $request->name === null || $request->name == '')
        {
            $request->name = $request->username;
        }

        $changed = false;

        if (auth()->user()->name != $request->name)
        {
            auth()->user()->name = $request->name;
            $changed = true;
        }

        if (auth()->user()->username != $request->username)
        {
            if (!User::where("username", "=", $request->username)->exists())
            {
                auth()->user()->username = $request->username;
                $changed = true;
            }
            else
            {
                return back()->withErrors(["username" => "$request->username is already taken"]);
            }
        }

        if (auth()->user()->email != $request->email)
        {
            if (!User::where("email", "=", $request->email)->exists())
            {
                auth()->user()->email = $request->email;
                $changed = true;

            }
            else
            {
                return back()->withErrors(["email" => "$request->email is already taken"]);
            }
        }

        if ($changed)
        {
            auth()->user()->save();
        }

        return back()->with("success", "Updated Profile");
    }

    public function notes()
    {
        return view("settings.notes");
    }

    public function security()
    {
        return view("settings.security");
    }

    public function updatePassword(Request $request)
    {
        $this->validate($request, [
            "old_password" => ["required", new MatchOldPassword],
            "password" => ["required", "confirmed", Password::min(8)->letters()->numbers()]
        ]);

        auth()->user()->password = Hash::make($request->password);
        auth()->user()->save();

        return back()->with("success", true);
    }

    public function enable2Fa(Request $request)
    {
        if (auth()->user()->has2Fa())
        {
            return redirect()->route("settings.security");
        }

        $google2fa = (new \PragmaRX\Google2FAQRCode\Google2FA());
        $secretKey = session("old_data")["secret"] ?? $google2fa->generateSecretKey();
        $qr = session("old_data")["qr"] ?? $google2fa->getQRCodeInline(
            config('app.name'),
            auth()->user()->email,
            $secretKey
        );

        $data = [
            "qr" => session("old_data")->qr ?? $qr,
            "secret" => session("old_data")->secret ?? $secretKey
        ];

        $request->session()->flash("data", $data);

        return view("auth.2fa_enable", $data);
    }

    public function auth2Fa(Request $request)
    {
        $this -> validate($request, [
            "otp" => "required"
        ]);

        $request->merge(session('data'));
        $google2fa = (new \PragmaRX\Google2FAQRCode\Google2FA());
        if ($google2fa->verifyKey($request->secret, $request->otp))
        {
            auth()->user()->otp_secret = Crypt::encrypt($request->secret);
            auth()->user()->save();
            return redirect()->route("settings.security")->with("2fa_enabled", "Activated 2 Factor Authentication");
        }

        $data = ["qr" => $request->qr, "secret" => $request->secret];
        $request->session()->flash("old_data", $data);
        return back()->withErrors(["otp" => "Incorrect Code, Please try again"]);
    }

    public function disable2Fa(Request $request)
    {
        if (!auth()->user()->has2Fa())
        {
            return redirect()->route("settings.security");
        }

        return view("auth.2fa_disable");
    }

    public function disable2FaConfirm(Request $request)
    {
        $this -> validate($request, [
            "otp" => "required"
        ]);

        $google2fa = (new \PragmaRX\Google2FAQRCode\Google2FA());

        if ($google2fa->verifyKey(Crypt::decrypt(auth()->user()->otp_secret), $request->otp))
        {
            auth()->user()->otp_secret = null;
            auth()->user()->save();
            return redirect()->route("settings.security")->with("2fa_disabled", "Disabled 2 Factor Authentication");
        }

        return back()->withErrors(["otp" => "Incorrect Code, Please try again"]);
    }

    public function activate2FactAuth(Request $request)
    {
        $google2fa = (new \PragmaRX\Google2FAQRCode\Google2FA());
        $secretKey = $google2fa->generateSecretKey();
        $qr = $google2fa->getQRCodeInline(
            config('app.name'),
            auth()->user()->email,
            $secretKey
        );

        return view("test", ["qr" => $qr, "secret" => $secretKey]);
    }
}

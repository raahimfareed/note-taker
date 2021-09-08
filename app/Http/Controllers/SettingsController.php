<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

    public function updateProfile()
    {
        //
    }

    public function notes()
    {
        return view("settings.notes");
    }

    public function security()
    {
        return view("settings.security");
    }

    public function updatePassword()
    {
        //
    }
}

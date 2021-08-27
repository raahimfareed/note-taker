<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DefaultController extends Controller
{
    public function __construct()
    {
        $this->middleware(["guest"]);
    }

    public function index()
    {
        return view("default.index");
    }
}

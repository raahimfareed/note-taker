<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\DefaultController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::get("/", [DefaultController::class, "index"])->name("index");

Route::get("/register", [RegisterController::class, "index"])->name("register");
Route::post("/register", [RegisterController::class, "store"]);

Route::get("/login", [LoginController::class, "index"])->name("login");
Route::post("/login", [LoginController::class, "store"]);

Route::post("/logout", [LogoutController::class, "store"])->name("logout");

Route::get("/home", [HomeController::class, "index"])->name("home");

Route::post("/note/new", [NoteController::class, "store"])->name("note.new");
Route::post("/note/update", [NoteController::class, "update"])->name("note.update");
Route::post("/note/get", [NoteController::class, "get"])->name("note.get");
Route::delete("/note/destroy", [NoteController::class, "destroy"])->name("note.delete");

Route::get("/settings", [SettingsController::class, "index"])->name("settings");
Route::get("/settings/profile", [SettingsController::class, "profile"])->name("settings.profile");
Route::get("/settings/security", [SettingsController::class, "security"])->name("settings.security");
Route::get("/settings/notes", [SettingsController::class, "notes"])->name("settings.notes");

Route::get("/test", function () {
    dd(Str::markdown("# Hello"));
});

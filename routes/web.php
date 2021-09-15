<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\DefaultController;
use App\Http\Controllers\ForgotPassword;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ResetPassword;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::get("/", [DefaultController::class, "index"])->name("index");

Route::get("/register", [RegisterController::class, "index"])->name("register");
Route::post("/register", [RegisterController::class, "store"]);

Route::get("/login", [LoginController::class, "index"])->name("login");
Route::post("/login", [LoginController::class, "store"]);

Route::get("/forgot-password", [ForgotPassword::class, "index"])->name("forgot-password");
Route::post("/forgot-password", [ForgotPassword::class, "store"]);
Route::get("/forgot-password/success", [ForgotPassword::class, "successPage"]);

Route::get("/reset-password/{token}", [ResetPassword::class, "index"])->name("password.reset");
Route::post("/reset-password", [ResetPassword::class, "store"])->name("password.update");

Route::get("/login/2fa", [LoginController::class, "index2Fa"])->name("login.2fa");
Route::post("/login/2fa", [LoginController::class, "store2Fa"]);

Route::post("/logout", [LogoutController::class, "store"])->name("logout");

Route::get("/home", [HomeController::class, "index"])->name("home");

Route::post("/note/new", [NoteController::class, "store"])->name("note.new");
Route::post("/note/update", [NoteController::class, "update"])->name("note.update");
Route::post("/note/get", [NoteController::class, "get"])->name("note.get");
Route::post("/note/share", [NoteController::class, "share"])->name("note.share");
Route::delete("/note/destroy", [NoteController::class, "destroy"])->name("note.delete");

Route::get("/settings", [SettingsController::class, "index"])->name("settings");

Route::get("/settings/profile", [SettingsController::class, "profile"])->name("settings.profile");
Route::post("/settings/profile", [SettingsController::class, "updateProfile"]);

Route::get("/settings/security", [SettingsController::class, "security"])->name("settings.security");
Route::post("/settings/security/update-password", [SettingsController::class, "updatePassword"])->name("settings.security.update-password");

Route::get("/settings/security/2fa/enable", [SettingsController::class, "enable2Fa"])->name("settings.security.2fa.enable");
Route::post("/settings/security/2fa/enable", [SettingsController::class, "auth2Fa"]);

Route::get("/settings/security/2fa/disable", [SettingsController::class, "disable2Fa"])->name("settings.security.2fa.disable");
Route::post("/settings/security/2fa/disable", [SettingsController::class, "disable2FaConfirm"]);

Route::get("/settings/notes", [SettingsController::class, "notes"])->name("settings.notes");

Route::get("/{user:username}/{note:share_token}", [HomeController::class, "share"])->name("note.share.index")->withoutMiddleware(["auth"]);
Route::post("/{user:username}/{note:share_token}", [NoteController::class, "get_shared"]);

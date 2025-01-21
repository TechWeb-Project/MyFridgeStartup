<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

Route::get('/', function () {
    return view('welcome');
});

// Autenticazione tramite le rotte di default
Auth::routes();

// home 
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//login 
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('login', [LoginController::class, 'login'])->name('login.post')->middleware('guest');

//la registrazione
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register'])->name('register.post');



Route::get('/prova', function() {
    return "endigay";
});

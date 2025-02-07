<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AggiuntaController;


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Home 
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])
    ->name('home')
    ->middleware('auth'); 

// Login
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('login', [LoginController::class, 'login'])->name('login.post')->middleware('guest');

// Registrazione
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register'])->name('register.post');

// Rotte per utenti autenticati
Route::middleware(['auth'])->group(function () {
    // Dashboard Amministratore
    Route::get('/admin/dashboard', function () {
        return view('dash_admin'); 
    })->name('admin.dashboard');

    // Dashboard Utente Normale
    Route::get('/user/dashboard', function () {
        return view('dash_user'); 
    })->name('user.dashboard');
});

 //rotta cambio immagine
Route::middleware(['auth'])->group(function () {
    Route::post('/user/update-profile-image', [UserController::class, 'updateProfileImage'])->name('user.updateProfileImage');
    Route::post('/user/update-password', [UserController::class, 'updatePassword'])->name('user.updatePassword');
});

// Route per ADD
Route::get('add', [AggiuntaController::class, 'create'])->name('add');

// Salva un nuovo alimento (POST)
Route::post('add', [AggiuntaController::class, 'store']);

// Visualizza la lista degli alimenti (GET, se desideri visualizzare la lista)
Route::get('alimenti', [AggiuntaController::class, 'index'])->name('alimenti.index');

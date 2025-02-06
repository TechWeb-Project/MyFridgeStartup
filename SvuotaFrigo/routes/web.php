<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
Route::get('/', function () {
    return redirect()->route('login');
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
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
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

  //rotta cambio password  
  Route::get('/cambia-password', [UserController::class, 'showChangePasswordPage'])->name('user.changePasswordPage');

//  rotta Logout
Route::get('/logout', function () {
    return redirect()->route('login');
});
//gestione immagine profilo utente
Route::post('/aggiorna-immagine-profilo', [UserController::class, 'updateProfileImage'])->name('user.updateProfileImage');


Route::middleware(['auth', 'can:admin'])->post('/admin/update-password', [AdminController::class, 'updatePassword'])->name('admin.updatePassword');

Route::middleware(['auth'])->group(function () {
Route::post('/admin/update-password', [AdminController::class, 'updatePassword'])->name('admin.updatePassword');
});
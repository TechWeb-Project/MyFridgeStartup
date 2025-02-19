<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;

use App\Http\Controllers\AggiuntaController;
use App\Http\Controllers\FrigoAIController;


Route::get('/', function () {
    return view('homepage');
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

//recupero password

Route::get('password/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');


// route for AI testing
Route::get('/testai', function () {
    return view('frigoai');
});
Route::post('/generate-recipe', [FrigoAIController::class, 'generateRecipe']);

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
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

//gestione immagine profilo utente
Route::post('/aggiorna-immagine-profilo', [UserController::class, 'updateProfileImage'])->name('user.updateProfileImage');


Route::middleware(['auth', 'can:admin'])->post('/admin/update-password', [AdminController::class, 'updatePassword'])->name('admin.updatePassword');

Route::middleware(['auth'])->group(function () {
Route::post('/admin/update-password', [AdminController::class, 'updatePassword'])->name('admin.updatePassword');
});

// Route per ADD
Route::get('add', [AggiuntaController::class, 'create'])->name('add');

// Salva un nuovo alimento (POST)
Route::post('add', [AggiuntaController::class, 'store']);

// Visualizza la lista degli alimenti (GET, se desideri visualizzare la lista)
Route::get('alimenti', [AggiuntaController::class, 'index'])->name('alimenti.index');

Route::middleware(['auth'])->group(function () {
    Route::post('/admin/update-role/{id}', [AdminController::class, 'updateRole'])->name('admin.updateRole');
    Route::post('/admin/delete-user/{id}', [AdminController::class, 'deleteUser'])->name('admin.deleteUser');
});

Route::post('/admin/update-role/{id}', [AdminController::class, 'updateUserRole'])
    ->name('admin.updateRole');


Route::delete('/admin/delete-user/{id}', [AdminController::class, 'deleteUser'])
    ->name('admin.deleteUser')
    ->middleware(['auth']);

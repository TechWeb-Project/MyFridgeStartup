<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AggiuntaController;
use App\Http\Controllers\RecipesGeneratorController;

use App\Http\Controllers\VisualizzatoreFrigoController;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserStatisticsController;
use App\Models\Prodotto;

use App\Http\Controllers\Admin\AdminStatisticsController;


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



// route for AI recipes generator (ora collegata alla view fridge_dashboard)
Route::middleware(['auth'])->group(function () {
    Route::post('/generate-recipe', [RecipesGeneratorController::class, 'generateRecipe'])->name('generate-recipe');
    Route::post('/save-error', [RecipesGeneratorController::class, 'saveError']);
    Route::post('/save-recipe', [RecipesGeneratorController::class, 'saveRecipe']);
    Route::post('/get-recipes', [RecipesGeneratorController::class, 'getRecipes']);
    Route::get('/check-auth', [RecipesGeneratorController::class, 'checkUserAuth']);
    Route::post('/update-fridge-quantities', [RecipesGeneratorController::class, 'updateFridgeQuantities']);
    Route::get('/get-remaining-recipes', [RecipesGeneratorController::class, 'getRemainingRecipes'])
        ->name('get.remaining.recipes')
        ->middleware('auth');
});

// Rotte per utenti autenticati
Route::middleware(['auth'])->group(function () {
    // Dashboard Utente Normale
    Route::get('/user/dashboard', function () {
        return view('dash_user');
    })->name('user.dashboard');
    // Dashboard statistiche utente premium
    Route::get('/user/statistics', [UserStatisticsController::class, 'index'])
        ->name('user.statistics')
        ->middleware(['auth']);
        
    Route::get('/user/statistics/data', [UserStatisticsController::class, 'getStatisticsData'])
        ->name('user.statistics.data')
        ->middleware(['auth']);
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

// Visualizza la lista degli alimenti (GET, se desideri visualizzare la lista)
Route::get('alimenti', [AggiuntaController::class, 'index'])->name('alimenti.index');

Route::middleware(['auth'])->post('/user/update-profile', [UserController::class, 'updateProfile'])->name('user.updateProfile');
// Main Fridge page

// Keep only one route for fridge_dashboard
Route::get('/fridge_dashboard', [VisualizzatoreFrigoController::class, 'mostraFrigo'])->name('fridge_dashboard');

// Remove or comment out these conflicting routes:
// Route::get('/fridge_dashboard', [MainFridgeController::class, 'index']);
// Route::get('/fridge_dashboard', [ProductController::class, 'show']);

// // // // //
// Mostra la pagina del frigo
Route::get('/fridge_dashboard', [VisualizzatoreFrigoController::class, 'mostraFrigo']);

// Route per inviare i dettagli dal frigo al div dettagli
Route::post('/get-product-details', [ProductController::class, 'getDetails']);
////////se non funziona meglio fridge_dashboard

// Route per inviare i prodotti selezionati al div recipes_generator
Route::post('/get-recipes', [RecipesGeneratorController::class, 'getRecipes']);
///////stessa cosa della route per il div di details

//Product
Route::delete('/product_details', [ProductController::class, 'destroy']);
Route::put('/product_details', [ProductController::class, 'updateProduct']);


//Route::post('/product_details', [ProductController::class, 'show'])->name('product.show');

Route::post('/product_details', [ProductController::class, 'getProductDetails'])->name('product.details');

// Route for admin pages (aggiungere anche middleware admin per pulizia codice e errori)
Route::middleware(['auth'])->group(function () {
    // Admin dashboard
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    
    // Admin statistics routes
    Route::get('/admin/statistics', [AdminStatisticsController::class, 'index'])->name('admin.statistics');
    Route::get('/admin/statistics/data', [AdminStatisticsController::class, 'getStatisticsData'])->name('admin.statistics.data');
    
    // Admin user management
    Route::post('/admin/update-role/{id}', [AdminController::class, 'updateUserRole'])->name('admin.updateRole');
    Route::delete('/admin/delete-user/{id}', [AdminController::class, 'deleteUser'])->name('admin.deleteUser');
    Route::post('/admin/update-password', [AdminController::class, 'updatePassword'])->name('admin.updatePassword');
});

Route::get('/fridge_dashboard', [VisualizzatoreFrigoController::class, 'mostraFrigo'])->name('fridge')->middleware('auth');
Route::post('/add_product', [AggiuntaController::class, 'store'])->name('add.product')->middleware('auth');

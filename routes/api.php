<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PlantsController;
use App\Http\Controllers\CategoriesController;

// Authentification
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/account/activate', [AuthController::class, 'activateAccount'])->name('activate');
Route::post('/password-reset', [AuthController::class, 'sendResetLink'])->name('password-reset');
Route::post('/password-reset/confirm', [AuthController::class, 'resetPassword'])->name('password-reset-confirm');

// Route::post('/logout', [AuthController::class, 'logout']);


Route::middleware('auth:api')->group(function () {
    
    // Déconnexion
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Routes protégées pour les administrateurs
    Route::middleware('role:admin')->group(function () {
        // categories
        Route::get('/categories', [CategoriesController::class, 'index']);
        Route::post('/categories', [CategoriesController::class, 'store']);
        Route::get('/categories/{slug}', [CategoriesController::class, 'show']);
        Route::put('/categories/{slug}', [CategoriesController::class, 'update']);
        Route::delete('/categories/{slug}', [CategoriesController::class, 'destroy']);
        
        // plants
        Route::get('/plants', [PlantsController::class, 'index']);
        Route::post('/plants', [PlantsController::class, 'store']);
        Route::get('/plants/{slug}', [PlantsController::class, 'show']);
        Route::put('/plants/{slug}', [PlantsController::class, 'update']);
        Route::delete('/plants/{slug}', [PlantsController::class, 'destroy']);
    });
    
    // routes protégées pour les employés
    Route::middleware('role:employee')->group(function () {
        Route::get('/reservations', [OrderController::class, 'index']);
        Route::get('/reservations/users/{id}', [OrderController::class, 'userOrders']);
        Route::get('/reservations/status/{status}', [OrderController::class, 'orderStatus']);
        Route::put('/reservations/{slug}', [OrderController::class, 'updateOrderStatus']);
        Route::delete('/reservations/{slug}', [OrderController::class, 'destroy']);
    });
    
    // routes protégées pour les touristes
    Route::middleware('role:cleint')->group(function () {
        //reservations
        Route::get('/reservations/users/{id}', [OrderController::class, 'userOrders']);
        Route::post('/reservations', [OrderController::class, 'store']);
        Route::get('/reservations/{slug}', [OrderController::class, 'show']);
        Route::put('/reservations/{slug}', [OrderController::class, 'update']);
        Route::delete('/reservations/{slug}', [OrderController::class, 'destroy']);
    });
});

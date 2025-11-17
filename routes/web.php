<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\AnimalController; 

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Rutas de autenticación
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Ruta principal redirige a login
Route::get('/', function () {
    return redirect()->route('login');
});

// Rutas protegidas
Route::middleware('web.auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Animales
    Route::get('/animals', [AnimalController::class, 'index'])->name('animals.index');
    Route::get('/animals/create', [AnimalController::class, 'create'])->name('animals.create');
    Route::post('/animals', [AnimalController::class, 'store'])->name('animals.store');
    Route::get('/animals/{id}', [AnimalController::class, 'show'])->name('animals.show');
    Route::get('/animals/{id}/edit', [AnimalController::class, 'edit'])->name('animals.edit');
    Route::put('/animals/{id}', [AnimalController::class, 'update'])->name('animals.update');
    Route::delete('/animals/{id}', [AnimalController::class, 'destroy'])->name('animals.destroy');
});
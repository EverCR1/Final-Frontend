<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\AnimalController;
use App\Http\Controllers\Web\FincaController;  
use App\Http\Controllers\Web\MedicamentoController;  
use App\Http\Controllers\Web\VacunacionController;
use App\Http\Controllers\Web\ProduccionLecheController;  
use App\Http\Controllers\Web\ReporteController;
use App\Http\Controllers\Web\UserController;   

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

// Rutas PÚBLICAS
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Ruta principal
Route::get('/', function () {
    return redirect()->route('login');
});

// ================= RUTAS PROTEGIDAS =================
Route::middleware(['web.auth'])->group(function () {
    
    // DASHBOARD - Todos los roles
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // ========== ANIMALES ==========
    

    // Create, Edit, Update - Admin y Veterinario
    Route::middleware(['role:admin,veterinario'])->group(function () {
        Route::get('/animals/create', [AnimalController::class, 'create'])->name('animals.create');
        Route::post('/animals', [AnimalController::class, 'store'])->name('animals.store');
        Route::get('/animals/{id}/edit', [AnimalController::class, 'edit'])->name('animals.edit');
        Route::put('/animals/{id}', [AnimalController::class, 'update'])->name('animals.update');
    });

    // Index y Show - Todos los roles
    Route::get('/animals', [AnimalController::class, 'index'])->name('animals.index');
    Route::get('/animals/{id}', [AnimalController::class, 'show'])->name('animals.show');
    
    // Eliminar - Solo Admin
    Route::middleware(['role:admin'])->group(function () {
        Route::delete('/animals/{id}', [AnimalController::class, 'destroy'])->name('animals.destroy');
    });
    
    // ========== FINCAS ==========
    // Solo Admin
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/fincas', [FincaController::class, 'index'])->name('fincas.index');
        Route::get('/fincas/create', [FincaController::class, 'create'])->name('fincas.create');
        Route::post('/fincas', [FincaController::class, 'store'])->name('fincas.store');
        Route::get('/fincas/{id}', [FincaController::class, 'show'])->name('fincas.show');
        Route::get('/fincas/{id}/edit', [FincaController::class, 'edit'])->name('fincas.edit');
        Route::put('/fincas/{id}', [FincaController::class, 'update'])->name('fincas.update');
        Route::delete('/fincas/{id}', [FincaController::class, 'destroy'])->name('fincas.destroy');
    });

    // ========== MEDICAMENTOS ==========
    // Solo Admin y Veterinario
    Route::middleware(['role:admin,veterinario'])->group(function () {
        Route::get('/medicamentos', [MedicamentoController::class, 'index'])->name('medicamentos.index');
        Route::get('/medicamentos/create', [MedicamentoController::class, 'create'])->name('medicamentos.create');
        Route::post('/medicamentos', [MedicamentoController::class, 'store'])->name('medicamentos.store');
        Route::get('/medicamentos/{id}', [MedicamentoController::class, 'show'])->name('medicamentos.show');
        Route::get('/medicamentos/{id}/edit', [MedicamentoController::class, 'edit'])->name('medicamentos.edit');
        Route::put('/medicamentos/{id}', [MedicamentoController::class, 'update'])->name('medicamentos.update');
        Route::delete('/medicamentos/{id}', [MedicamentoController::class, 'destroy'])->name('medicamentos.destroy');
    });

    // ========== VACUNACIONES ==========
    // Solo Admin y Veterinario
    Route::middleware(['role:admin,veterinario'])->group(function () {
        Route::get('/vacunaciones', [VacunacionController::class, 'index'])->name('vacunaciones.index');
        Route::get('/vacunaciones/create', [VacunacionController::class, 'create'])->name('vacunaciones.create');
        Route::post('/vacunaciones', [VacunacionController::class, 'store'])->name('vacunaciones.store');
        Route::get('/vacunaciones/{id}', [VacunacionController::class, 'show'])->name('vacunaciones.show');
        Route::get('/vacunaciones/{id}/edit', [VacunacionController::class, 'edit'])->name('vacunaciones.edit');
        Route::put('/vacunaciones/{id}', [VacunacionController::class, 'update'])->name('vacunaciones.update');
        Route::delete('/vacunaciones/{id}', [VacunacionController::class, 'destroy'])->name('vacunaciones.destroy');
    });

    // ========== PRODUCCIÓN LECHE ==========
    // Todos los roles pueden acceder (Index, Show, Reportes)
    Route::get('/produccion-leche/create', [ProduccionLecheController::class, 'create'])->name('produccion-leche.create');
    Route::post('/produccion-leche', [ProduccionLecheController::class, 'store'])->name('produccion-leche.store');
    Route::post('/produccion-leche', [ProduccionLecheController::class, 'store'])->name('produccion-leche.store');
    Route::get('/produccion-leche/{id}/edit', [ProduccionLecheController::class, 'edit'])->name('produccion-leche.edit');
    Route::put('/produccion-leche/{id}', [ProduccionLecheController::class, 'update'])->name('produccion-leche.update');
    Route::get('/produccion-leche', [ProduccionLecheController::class, 'index'])->name('produccion-leche.index');
    Route::get('/produccion-leche/{id}', [ProduccionLecheController::class, 'show'])->name('produccion-leche.show');
    Route::get('/produccion-leche/reportes/form', [ProduccionLecheController::class, 'reportes'])->name('produccion-leche.reportes');
    Route::post('/produccion-leche/reportes/generar', [ProduccionLecheController::class, 'generarReporte'])->name('produccion-leche.generar-reporte');
    Route::get('/produccion-leche/grafica-dashboard', [ProduccionLecheController::class, 'datosGraficaDashboard'])->name('produccion-leche.grafica-dashboard');

    // Create, Edit, Update, Delete - Admin y Veterinario
    Route::middleware(['role:admin,veterinario'])->group(function () {
        Route::delete('/produccion-leche/{id}', [ProduccionLecheController::class, 'destroy'])->name('produccion-leche.destroy');
    });

    // ========== REPORTES GENERALES ==========
    // Solo Admin puede acceder
    Route::middleware(['role:admin'])->group(function () {
        Route::prefix('reportes')->group(function () {
            Route::get('/', [ReporteController::class, 'index'])->name('reportes.index');
            Route::get('/animales-por-finca', [ReporteController::class, 'animalesPorFinca'])->name('reportes.animales-finca');
            Route::get('/produccion-mensual', [ReporteController::class, 'produccionMensual'])->name('reportes.produccion-mensual');
            Route::get('/salud-animal', [ReporteController::class, 'reporteSalud'])->name('reportes.salud-animal');
        });
    });

    // ========== USUARIOS ==========
    // Solo Administrador
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
        Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
        
        // Rutas adicionales para usuarios
        Route::get('/users/estadisticas', [UserController::class, 'estadisticas'])->name('users.estadisticas');
        Route::get('/veterinarios', [UserController::class, 'veterinarios'])->name('users.veterinarios');
        Route::get('/productores', [UserController::class, 'productores'])->name('users.productores');
    });
});
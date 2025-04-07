<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CicloController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EstadosCicloController;
use App\Http\Controllers\EstadosDeudaController;
use App\Http\Controllers\EstadosPedidoController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MensajeDeBienvenidaController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PruebaController;
use App\Http\Controllers\SuperadminController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;

// Ruta principal (login)
Route::get('/', function () {
    return view('auth.login');
})->name('login');

// Rutas públicas (sin autenticación)
Route::middleware(['web'])->group(function () {
    // Registro de usuarios
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisterController::class, 'register'])->name('register.store');

    // Recuperación de contraseña
    Route::get('password/reset', [ResetPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [ResetPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

    // Login
    Route::post('login', [LoginController::class, 'login'])->name('login.post');
});

// Rutas protegidas (requieren autenticación)
Route::middleware(['auth'])->group(function () {
    // Dashboard temporal
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Rutas para Superadmin
    Route::get('/superadmin/dashboard', [SuperadminController::class, 'index'])->name('superadmin.dashboard');

    // Rutas para Admin
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // Rutas para Cliente
    Route::get('/cliente/dashboard', [ClienteController::class, 'index'])->name('cliente.dashboard');

    // Rutas protegidas con recursos
    Route::resource('estados-ciclos', EstadosCicloController::class)->names([
        'index' => 'estados-ciclos.index',
        'create' => 'estados-ciclos.create',
        'store' => 'estados-ciclos.store',
        'show' => 'estados-ciclos.show',
        'edit' => 'estados-ciclos.edit',
        'update' => 'estados-ciclos.update',
        'destroy' => 'estados-ciclos.destroy',
    ]);

    Route::resource('ciclos', CicloController::class)->names([
        'index' => 'ciclos.index',
        'create' => 'ciclos.create',
        'store' => 'ciclos.store',
        'show' => 'ciclos.show',
        'edit' => 'ciclos.edit',
        'update' => 'ciclos.update',
        'destroy' => 'ciclos.destroy',
    ]);

    Route::resource('clientes', ClienteController::class)->names([
        'index' => 'clientes.index',
        'create' => 'clientes.create',
        'store' => 'clientes.store',
        'show' => 'clientes.show',
        'edit' => 'clientes.edit',
        'update' => 'clientes.update',
        'destroy' => 'clientes.destroy',
    ]);

    Route::resource('productos', ProductoController::class)->names([
        'index' => 'productos.index',
        'create' => 'productos.create',
        'store' => 'productos.store',
        'show' => 'productos.show',
        'edit' => 'productos.edit',
        'update' => 'productos.update',
        'destroy' => 'productos.destroy',
    ]);

    Route::resource('estados-pedidos', EstadosPedidoController::class)->names([
        'index' => 'estados-pedidos.index',
        'create' => 'estados-pedidos.create',
        'store' => 'estados-pedidos.store',
        'show' => 'estados-pedidos.show',
        'edit' => 'estados-pedidos.edit',
        'update' => 'estados-pedidos.update',
        'destroy' => 'estados-pedidos.destroy',
    ]);

    Route::resource('estados-deudas', EstadosDeudaController::class)->names([
        'index' => 'estados-deudas.index',
        'create' => 'estados-deudas.create',
        'store' => 'estados-deudas.store',
        'show' => 'estados-deudas.show',
        'edit' => 'estados-deudas.edit',
        'update' => 'estados-deudas.update',
        'destroy' => 'estados-deudas.destroy',
    ]);

    Route::resource('pedidos', PedidoController::class)->names([
        'index' => 'pedidos.index',
        'create' => 'pedidos.create',
        'store' => 'pedidos.store',
        'show' => 'pedidos.show',
        'edit' => 'pedidos.edit',
        'update' => 'pedidos.update',
        'destroy' => 'pedidos.destroy',
    ]);

    Route::resource('mensaje-de-bienvenidas', MensajeDeBienvenidaController::class)->names([
        'index' => 'mensaje-de-bienvenidas.index',
        'create' => 'mensaje-de-bienvenidas.create',
        'store' => 'mensaje-de-bienvenidas.store',
        'show' => 'mensaje-de-bienvenidas.show',
        'edit' => 'mensaje-de-bienvenidas.edit',
        'update' => 'mensaje-de-bienvenidas.update',
        'destroy' => 'mensaje-de-bienvenidas.destroy',
    ]);

    // Rutas de pruebas
    Route::resource('pruebas', PruebaController::class)->names([
        'index' => 'pruebas.index',
        'create' => 'pruebas.create',
        'store' => 'pruebas.store',
        'show' => 'pruebas.show',
        'edit' => 'pruebas.edit',
        'update' => 'pruebas.update',
        'destroy' => 'pruebas.destroy',
    ]);

    // Logout
    Route::get('/logout', function () {
        Auth::logout();
        session()->invalidate();

        return redirect('/');
    })->name('logout');
});

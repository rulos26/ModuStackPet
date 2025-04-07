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

// Página principal muestra el login
Route::get('/', function () {
    return view('auth.login');
})->name('login');

// Mostrar formulario de login (GET) y procesar login (POST)
Route::get('login', [LoginController::class, 'login'])->name('login.form');
Route::post('login', [LoginController::class, 'authenticate'])->name('login.perform');

// Logout
Route::get('/logout', function () {
    Auth::logout();
    session()->invalidate();
    return redirect('/');
})->name('logout');

// Registro de usuario
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Restablecimiento de contraseña
Route::get('password/reset', [ResetPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ResetPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// Panel general (puedes redirigir según el tipo de usuario)
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// Rutas para Superadmin
Route::get('/superadmin/dashboard', [SuperadminController::class, 'index'])->name('superadmin.dashboard');

// Rutas para Admin
Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

// Rutas para Cliente
Route::get('/cliente/dashboard', [ClienteController::class, 'index'])->name('cliente.dashboard');

// Recursos generales
Route::resource('pruebas', PruebaController::class);
Route::resource('estados-ciclos', EstadosCicloController::class);
Route::resource('ciclos', CicloController::class);
Route::resource('clientes', ClienteController::class);
Route::resource('productos', ProductoController::class);
Route::resource('estados-pedidos', EstadosPedidoController::class);
Route::resource('estados-deudas', EstadosDeudaController::class);
Route::resource('pedidos', PedidoController::class);
Route::resource('mensaje-de-bienvenidas', MensajeDeBienvenidaController::class);

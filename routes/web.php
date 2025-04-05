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
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PruebaController;
use App\Http\Controllers\SuperadminController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['web'])->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
});

// Ruta para mostrar el formulario de registro
Route::get('register', [RegisterController::class, 'showRegisterForm'])->name('register');

// Ruta para manejar el registro
Route::post('register', [RegisterController::class, 'register']);

Route::get('password/reset', [ResetPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ResetPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('temp.index');
Route::resource('pruebas', PruebaController::class);
Route::post('login', [LoginController::class, 'login'])->name('login');

// Rutas para Superadmin
Route::get('/superadmin/dashboard', [SuperadminController::class, 'index'])->name('superadmin.dashboard');

// Rutas para Admin
Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

// Rutas para Cliente
Route::get('/cliente/dashboard', [ClienteController::class, 'index'])->name('cliente.dashboard');
Route::resource('estados-ciclos', EstadosCicloController::class);
Route::resource('ciclos', CicloController::class);
Route::resource('clientes', ClienteController::class);
Route::resource('productos', ProductoController::class);
Route::resource('estados-pedidos', EstadosPedidoController::class);
Route::resource('estados-deudas', EstadosDeudaController::class);
Route::resource('pedidos', PedidoController::class);

Route::get('/logout', function () {
    Auth::logout();
    session()->invalidate();

    return redirect('/');
})->name('logout');

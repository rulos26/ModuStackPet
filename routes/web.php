<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\BarrioController;
use App\Http\Controllers\CicloController;
use App\Http\Controllers\CiudadController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\EstadosCicloController;
use App\Http\Controllers\EstadosDeudaController;
use App\Http\Controllers\EstadosPedidoController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MascotaController;
use App\Http\Controllers\MensajeDeBienvenidaController;
use App\Http\Controllers\PaseadorController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PruebaController;
use App\Http\Controllers\RazaController;
use App\Http\Controllers\RoleAssignmentController;
use App\Http\Controllers\SectoreController;
use App\Http\Controllers\SuperadminController;
use App\Http\Controllers\TipoDocumentoController;
use App\Http\Controllers\TiposEmpresaController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;
use Illuminate\Http\Request;
use App\Http\Controllers\VacunasCertificacionesController;

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

Route::get('login', [LoginController::class, 'login'])->name('login');

// Rutas para Superadmin
Route::get('/superadmin/dashboard', [SuperadminController::class, 'index'])->name('superadmin.dashboard');

// Rutas para Admin
Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

Route::get('/clientes/dashboard', [ClienteController::class, 'index'])->name('cliente.dashboard');

route::get('/paseador/dashboard', [PaseadorController::class, 'index'])->name('paseador.dashboard');
/* // Rutas para Admin
Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('home'); */

// Rutas para Cliente

Route::resource('mensaje-de-bienvenidas', MensajeDeBienvenidaController::class);
Route::get('/logout', function () {
    Auth::logout();
    session()->invalidate();

    return redirect('/');
})->name('logout');

// Middleware para proteger el dashboard
Route::middleware(['auth', 'verified'])->group(function () {
    // Rutas para Superadmin
Route::get('/superadmin/dashboard', [SuperadminController::class, 'index'])->name('superadmin.dashboard');
});

// Ruta para verificar email
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// Ruta que maneja el enlace de verificación
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/superadmin/dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

// Reenvío de verificación
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', '¡Se ha enviado un nuevo enlace de verificación!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::post('/notificaciones/leidas', function () {
    auth()->user()->unreadNotifications->markAsRead();
    return back();
})->name('notificaciones.marcar.leidas');
Route::resource('users', UserController::class);
Route::resource('tipo-documentos', TipoDocumentoController::class);
Route::middleware(['auth'])->group(function () {
    Route::get('/usuarios/roles', [RoleAssignmentController::class, 'index'])->name('usuarios.roles.index');
    Route::post('/usuarios/roles/{user}', [RoleAssignmentController::class, 'asignarRoles'])->name('usuarios.roles.asignar');
});
Route::resource('mascotas', MascotaController::class);
Route::resource('razas', RazaController::class);
Route::resource('barrios', BarrioController::class);
Route::get('/pdf', [PDFController::class, 'generarPDF'])->name('pdf.generar');
Route::get('/pdf/mascota', [PDFController::class, 'generarPDFMascota'])->name('pdf.mascota');
Route::resource('vacunas_certificaciones', VacunasCertificacionesController::class);
Route::resource('departamentos', DepartamentoController::class);
Route::resource('ciudades', CiudadController::class);
Route::post('ciudades/{ciudad}/toggle-status', [CiudadController::class, 'toggleStatus'])->name('ciudades.toggle-status');
Route::resource('sectores', SectoreController::class);
Route::resource('tipos-empresas', TiposEmpresaController::class);
Route::resource('empresas', EmpresaController::class);
Route::get('api/ciudades/{departamentoId}', [EmpresaController::class, 'getCiudades'])->name('empresas.ciudades');


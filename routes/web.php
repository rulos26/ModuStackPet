<?php

use Illuminate\Http\Request;
use App\Http\Controllers\VacunasCertificacionesController;
use App\Http\Controllers\SuperadminController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\PaseadorController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetPasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MensajeDeBienvenidaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TipoDocumentoController;
use App\Http\Controllers\RoleAssignmentController;
use App\Http\Controllers\MascotaController;
use App\Http\Controllers\RazaController;
use App\Http\Controllers\BarrioController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\CiudadController;
use App\Http\Controllers\SectoreController;
use App\Http\Controllers\TiposEmpresaController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\PathDocumentoController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;


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
Route::get('/superadmin/dashboard', [SuperadminController::class, 'login_Superadmin'])->name('superadmin.dashboard');

// Rutas para Admin
Route::get('/admin/dashboard', [AdminController::class, 'login_Admin'])->name('admin.dashboard');

Route::get('/clientes/dashboard', [ClienteController::class, 'login_Cliente'])->name('cliente.dashboard');


route::get('/paseador/dashboard', [PaseadorController::class, 'login_Paseador'])->name('paseador.dashboard');
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


Route::resource('paths-documentos', PathDocumentoController::class);
Route::get('/paths-documentos', [PathDocumentoController::class, 'index'])->name('paths-documentos.index');
Route::get('/paths-documentos/create', [PathDocumentoController::class, 'create'])->name('paths-documentos.create');
Route::post('/paths-documentos', [PathDocumentoController::class, 'store'])->name('paths-documentos.store');
Route::post('paths-documentos/{pathDocumento}/toggle-status', [PathDocumentoController::class, 'toggleStatus'])->name('paths-documentos.toggle-status');

// Rutas para administradores
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::resource('users', AdminController::class);
    Route::post('/users/{user}/toggle-status', [AdminController::class, 'toggleStatus'])->name('users.toggle-status');
});

// Rutas para clientes
Route::middleware(['auth'])->prefix('cliente')->name('cliente.')->group(function () {
    Route::get('/dashboard', [ClienteController::class, 'login_Cliente'])->name('dashboard');
    Route::get('/perfil', [ClienteController::class, 'index'])->name('perfil.index');
    Route::get('/perfil/{user}', [ClienteController::class, 'show'])->name('perfil.show');
    Route::get('/perfil/{user}/edit', [ClienteController::class, 'edit'])->name('perfil.edit');
    Route::put('/perfil/{user}', [ClienteController::class, 'update'])->name('perfil.update');
});

// Rutas para paseadores
Route::middleware(['auth'])->prefix('paseador')->name('paseador.')->group(function () {
    Route::get('/dashboard', [PaseadorController::class, 'login_Paseador'])->name('dashboard');
    Route::get('/perfil', [PaseadorController::class, 'index'])->name('perfil.index');
    Route::get('/perfil/{user}', [PaseadorController::class, 'show'])->name('perfil.show');
    Route::get('/perfil/{user}/edit', [PaseadorController::class, 'edit'])->name('perfil.edit');
    Route::put('/perfil/{user}', [PaseadorController::class, 'update'])->name('perfil.update');
});

// Rutas para superadmin
Route::middleware(['auth'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [SuperadminController::class, 'index'])->name('dashboard');
    Route::get('/users/edit', [SuperadminController::class, 'edit'])->name('users.edit');
    Route::get('/users/show', [SuperadminController::class, 'show'])->name('users.show');
    Route::post('/users/change-password', [SuperadminController::class, 'changePassword'])->name('users.change-password');
    Route::post('/users/{user}/toggle-status', [SuperadminController::class, 'toggleStatus'])->name('users.toggle-status');
});

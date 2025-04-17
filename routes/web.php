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


// RUTAS PÚBLICAS
// Ruta principal - Muestra el login
Route::get('/', function () {
    return view('auth.login');
});

// Rutas de autenticación
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Rutas de registro (No requieren autenticación pero deberían estar protegidas por guest middleware)
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::get('register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

// Rutas de recuperación de contraseña (No requieren autenticación)
Route::get('password/reset', [ResetPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ResetPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// RUTAS DE DASHBOARD
// Dashboard temporal (Requiere autenticación)
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('temp.index');

// Dashboards por rol (Requieren autenticación y middleware de rol)
Route::get('/superadmin/dashboard', [SuperadminController::class, 'index'])->name('superadmin.dashboard');
Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
Route::get('/clientes/dashboard', [ClienteController::class, 'index'])->name('cliente.dashboard');
Route::get('/paseador/dashboard', [PaseadorController::class, 'index'])->name('paseador.dashboard');

// RUTAS DE CONFIGURACIÓN
// Mensaje de bienvenida (Usado en sidebar, requiere autenticación)
Route::resource('mensaje-de-bienvenidas', MensajeDeBienvenidaController::class);

// Gestión de usuarios y roles (Usados en sidebar, requieren autenticación y rol superadmin/admin)
Route::resource('users', UserController::class);
Route::resource('tipo-documentos', TipoDocumentoController::class);
Route::get('/usuarios/roles', [RoleAssignmentController::class, 'index'])->name('usuarios.roles.index');
Route::post('/usuarios/roles/{user}', [RoleAssignmentController::class, 'asignarRoles'])->name('usuarios.roles.asignar');

// RUTAS DE MASCOTAS Y RELACIONADOS (Usadas en sidebar según rol)
Route::resource('mascotas', MascotaController::class);
Route::resource('razas', RazaController::class);
Route::resource('barrios', BarrioController::class);
Route::resource('vacunas_certificaciones', VacunasCertificacionesController::class);

// RUTAS DE UBICACIÓN (Usadas en sidebar, requieren autenticación y rol superadmin/admin)
Route::resource('departamentos', DepartamentoController::class);
Route::resource('ciudades', CiudadController::class);
Route::post('ciudades/{ciudad}/toggle-status', [CiudadController::class, 'toggleStatus'])->name('ciudades.toggle-status');
Route::resource('sectores', SectoreController::class);

// RUTAS DE EMPRESAS (Usadas en sidebar, requieren autenticación y rol superadmin/admin)
Route::resource('tipos-empresas', TiposEmpresaController::class);
Route::resource('empresas', EmpresaController::class);
Route::get('api/ciudades/{departamentoId}', [EmpresaController::class, 'getCiudades'])->name('empresas.ciudades');

// RUTAS DE DOCUMENTOS (Usadas en sidebar, requieren autenticación y rol superadmin/admin)
Route::resource('paths-documentos', PathDocumentoController::class);
Route::get('/paths-documentos', [PathDocumentoController::class, 'index'])->name('paths-documentos.index');
Route::get('/paths-documentos/create', [PathDocumentoController::class, 'create'])->name('paths-documentos.create');
Route::post('/paths-documentos', [PathDocumentoController::class, 'store'])->name('paths-documentos.store');
Route::post('paths-documentos/{pathDocumento}/toggle-status', [PathDocumentoController::class, 'toggleStatus'])->name('paths-documentos.toggle-status');

// RUTAS DE PDF (Usadas en sidebar, visibles para todos los roles autenticados)
Route::get('/pdf', [PDFController::class, 'generarPDF'])->name('pdf.generar');
Route::get('/pdf/mascota', [PDFController::class, 'generarPDFMascota'])->name('pdf.mascota');

// RUTAS DE NOTIFICACIONES (Requieren autenticación)
Route::post('/notificaciones/leidas', function () {
    auth()->user()->unreadNotifications->markAsRead();
    return back();
})->name('notificaciones.marcar.leidas');

// RUTAS DE VERIFICACIÓN DE EMAIL (No requieren autenticación)
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/superadmin/dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', '¡Se ha enviado un nuevo enlace de verificación!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

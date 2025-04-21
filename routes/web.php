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
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // Rutas de gestión de usuarios para admin
    Route::get('/admin/users', [AdminController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/create', [AdminController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [AdminController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{user}', [AdminController::class, 'show'])->name('admin.users.show');
    Route::get('/admin/users/{user}/edit', [AdminController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [AdminController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [AdminController::class, 'destroy'])->name('admin.users.destroy');
});

// Rutas para clientes
Route::middleware(['auth', 'role:cliente'])->group(function () {
    Route::get('/cliente/dashboard', [ClienteController::class, 'login_Cliente'])->name('cliente.dashboard');

    // Rutas de perfil para cliente
    Route::get('/cliente/perfil', [ClienteController::class, 'index'])->name('cliente.perfil.index');
    Route::get('/cliente/perfil/{user}', [ClienteController::class, 'show'])->name('cliente.perfil.show');
    Route::get('/cliente/perfil/{user}/edit', [ClienteController::class, 'edit'])->name('cliente.perfil.edit');
    Route::put('/cliente/perfil/{user}', [ClienteController::class, 'update'])->name('cliente.perfil.update');
});

// Rutas para paseadores
Route::middleware(['auth', 'role:paseador'])->group(function () {
    Route::get('/paseador/dashboard', [PaseadorController::class, 'login_Paseador'])->name('paseador.dashboard');

    // Rutas de perfil para paseador
    Route::get('/paseador/perfil', [PaseadorController::class, 'index'])->name('paseador.perfil.index');
    Route::get('/paseador/perfil/{user}', [PaseadorController::class, 'show'])->name('paseador.perfil.show');
    Route::get('/paseador/perfil/{user}/edit', [PaseadorController::class, 'edit'])->name('paseador.perfil.edit');
    Route::put('/paseador/perfil/{user}', [PaseadorController::class, 'update'])->name('paseador.perfil.update');
});

// Rutas para superadmin
Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::get('/superadmin/dashboard', [SuperadminController::class, 'index'])->name('superadmin.dashboard');

    // Rutas de gestión de usuarios para superadmin
    Route::get('/superadmin/users', [SuperadminController::class, 'index'])->name('superadmin.users.index');
    Route::get('/superadmin/users/create', [SuperadminController::class, 'create'])->name('superadmin.users.create');
    Route::post('/superadmin/users', [SuperadminController::class, 'store'])->name('superadmin.users.store');
    Route::get('/superadmin/users/show', [SuperadminController::class, 'show'])->name('superadmin.users.show');
    Route::get('/superadmin/users/{user}/edit', [SuperadminController::class, 'edit'])->name('superadmin.users.edit');
    Route::put('/superadmin/users/{user}', [SuperadminController::class, 'update'])->name('superadmin.users.update');
    Route::delete('/superadmin/users/{user}', [SuperadminController::class, 'destroy'])->name('superadmin.users.destroy');
    Route::post('/superadmin/users/{user}/toggle-status', [SuperadminController::class, 'toggleStatus'])->name('superadmin.users.toggle-status');
});

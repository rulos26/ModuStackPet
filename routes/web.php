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
use App\Http\Controllers\PathDocumentoController;
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

// RUTAS PÚBLICAS
// Estas rutas son accesibles sin necesidad de autenticación
Route::get('/', function () {
    return view('auth.login');
});

// Rutas de registro de usuarios
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::get('register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

// Rutas de recuperación de contraseña
Route::get('password/reset', [ResetPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ResetPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// Ruta de inicio de sesión
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// RUTAS PROTEGIDAS
// Cada ruta tiene su middleware de autenticación
// La validación de roles se maneja en cada controlador

// Dashboard temporal
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('temp.index');

// Dashboards específicos
Route::get('/superadmin/dashboard', [SuperadminController::class, 'index'])->middleware(['auth'])->name('superadmin.dashboard');
Route::get('/admin/dashboard', [AdminController::class, 'index'])->middleware(['auth'])->name('admin.dashboard');
Route::get('/clientes/dashboard', [ClienteController::class, 'index'])->middleware(['auth'])->name('cliente.dashboard');
Route::get('/paseador/dashboard', [PaseadorController::class, 'index'])->middleware(['auth'])->name('paseador.dashboard');

// Configuración del sistema
Route::resource('mensaje-de-bienvenidas', MensajeDeBienvenidaController::class)->middleware(['auth']);
Route::resource('users', UserController::class)->middleware(['auth']);
Route::resource('tipo-documentos', TipoDocumentoController::class)->middleware(['auth']);
Route::get('/usuarios/roles', [RoleAssignmentController::class, 'index'])->middleware(['auth'])->name('usuarios.roles.index');
Route::post('/usuarios/roles/{user}', [RoleAssignmentController::class, 'asignarRoles'])->middleware(['auth'])->name('usuarios.roles.asignar');

// Gestión de mascotas y servicios relacionados
Route::resource('mascotas', MascotaController::class)->middleware(['auth']);
Route::resource('razas', RazaController::class)->middleware(['auth']);
Route::resource('barrios', BarrioController::class)->middleware(['auth']);
Route::resource('vacunas_certificaciones', VacunasCertificacionesController::class)->middleware(['auth']);

// Gestión de ubicaciones geográficas
Route::resource('departamentos', DepartamentoController::class)->middleware(['auth']);
Route::resource('ciudades', CiudadController::class)->middleware(['auth']);
Route::post('ciudades/{ciudad}/toggle-status', [CiudadController::class, 'toggleStatus'])->middleware(['auth'])->name('ciudades.toggle-status');
Route::resource('sectores', SectoreController::class)->middleware(['auth']);

// Gestión de empresas
Route::resource('tipos-empresas', TiposEmpresaController::class)->middleware(['auth']);
Route::resource('empresas', EmpresaController::class)->middleware(['auth']);
Route::get('api/ciudades/{departamentoId}', [EmpresaController::class, 'getCiudades'])->middleware(['auth'])->name('empresas.ciudades');

// Gestión de documentos
Route::resource('paths-documentos', PathDocumentoController::class)->middleware(['auth']);
Route::post('paths-documentos/{pathDocumento}/toggle-status', [PathDocumentoController::class, 'toggleStatus'])->middleware(['auth'])->name('paths-documentos.toggle-status');

// Generación de PDFs
Route::get('/pdf', [PDFController::class, 'generarPDF'])->middleware(['auth'])->name('pdf.generar');
Route::get('/pdf/mascota', [PDFController::class, 'generarPDFMascota'])->middleware(['auth'])->name('pdf.mascota');

// Sistema de notificaciones
Route::post('/notificaciones/leidas', function () {
    Auth::user()->unreadNotifications->markAsRead();
    return back();
})->middleware(['auth'])->name('notificaciones.marcar.leidas');

// RUTAS DE VERIFICACIÓN DE EMAIL
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware(['auth'])->name('verification.notice');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', '¡Se ha enviado un nuevo enlace de verificación!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/superadmin/dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

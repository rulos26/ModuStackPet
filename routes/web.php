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
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\MigrationController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\SeederController;
use App\Http\Controllers\CleanController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Barrio;


// Rutas API para barrios (sin prefijo /api para evitar problemas de routing)
Route::get('/barrios-engativa', function () {
    try {
        $barrios = Barrio::where('localidad', 'Engativá')
            ->orWhere('localidad', 'like', '%Engativá%')
            ->orWhere('localidad', 'like', '%Engativa%')
            ->orderBy('nombre')
            ->get(['id', 'nombre', 'localidad']);
        
        return response()->json($barrios, 200, [], JSON_UNESCAPED_UNICODE);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error al cargar barrios', 'message' => $e->getMessage()], 500);
    }
})->name('barrios.engativa');

// Ruta para obtener barrios por ciudad (mantener para compatibilidad)
Route::get('/barrios-por-ciudad/{ciudadId}', function ($ciudadId) {
    try {
        // Solo devolver barrios de Engativá independientemente de la ciudad
        $barrios = Barrio::where('localidad', 'Engativá')
            ->orWhere('localidad', 'like', '%Engativá%')
            ->orWhere('localidad', 'like', '%Engativa%')
            ->orderBy('nombre')
            ->get(['id', 'nombre', 'localidad']);
        
        return response()->json($barrios, 200, [], JSON_UNESCAPED_UNICODE);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error al cargar barrios', 'message' => $e->getMessage()], 500);
    }
})->name('barrios.by-ciudad');

Route::get('/', function () {
    // Si el usuario está autenticado, redirigir según su rol
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->hasRole('Superadmin')) {
            return redirect()->route('superadmin.dashboard');
        } elseif ($user->hasRole('Admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('Cliente')) {
            return redirect()->route('cliente.dashboard');
        } elseif ($user->hasRole('Paseador')) {
            return redirect()->route('paseador.dashboard');
        }
        return redirect()->route('temp.index');
    }

    // Si no está autenticado, mostrar login
    return redirect()->route('login');
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

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);

// Rutas OAuth para autenticación con redes sociales
Route::get('/auth/{provider}', [\App\Http\Controllers\Auth\SocialAuthController::class, 'redirect'])->name('social.redirect');
Route::get('/auth/{provider}/callback', [\App\Http\Controllers\Auth\SocialAuthController::class, 'callback'])->name('social.callback');

// Rutas para Superadmin
Route::get('/superadmin/dashboard', [SuperadminController::class, 'login_Superadmin'])->name('superadmin.dashboard');

// Rutas para Admin
Route::get('/admin/dashboard', [AdminController::class, 'login_Admin'])->name('admin.dashboard');

Route::get('/clientes/dashboard', [ClienteController::class, 'login_Cliente'])->name('cliente.dashboard');


route::get('/paseador/dashboard', [PaseadorController::class, 'login_Paseador'])->name('paseador.dashboard');
/* // Rutas para Admin
Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('home'); */

// Rutas para Cliente

Route::middleware([\App\Http\Middleware\CheckModuleStatus::class . ':bienvenida'])->group(function () {
    Route::resource('mensaje-de-bienvenidas', MensajeDeBienvenidaController::class);
});
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
Route::middleware([\App\Http\Middleware\CheckModuleStatus::class . ':tipo-documentos'])->group(function () {
    Route::resource('tipo-documentos', TipoDocumentoController::class);
});
Route::middleware(['auth'])->group(function () {
Route::get('/usuarios/roles', [RoleAssignmentController::class, 'index'])->name('usuarios.roles.index');
Route::post('/usuarios/roles/{user}', [RoleAssignmentController::class, 'asignarRoles'])->name('usuarios.roles.asignar');
});

Route::middleware([\App\Http\Middleware\CheckModuleStatus::class . ':mascotas'])->group(function () {
    Route::resource('mascotas', MascotaController::class);
});
Route::middleware([\App\Http\Middleware\CheckModuleStatus::class . ':razas'])->group(function () {
    Route::resource('razas', RazaController::class);
});
Route::middleware([\App\Http\Middleware\CheckModuleStatus::class . ':barrios'])->group(function () {
    Route::resource('barrios', BarrioController::class);
});
Route::middleware([\App\Http\Middleware\CheckModuleStatus::class . ':reportes'])->group(function () {
    Route::get('/pdf', [PDFController::class, 'generarPDF'])->name('pdf.generar');
    Route::get('/pdf/mascota', [PDFController::class, 'generarPDFMascota'])->name('pdf.mascota');
});
Route::middleware([\App\Http\Middleware\CheckModuleStatus::class . ':certificados'])->group(function () {
    Route::resource('vacunas_certificaciones', VacunasCertificacionesController::class);
});


Route::middleware([\App\Http\Middleware\CheckModuleStatus::class . ':departamentos'])->group(function () {
    Route::resource('departamentos', DepartamentoController::class);
});
Route::middleware([\App\Http\Middleware\CheckModuleStatus::class . ':ciudades'])->group(function () {
    Route::resource('ciudades', CiudadController::class);
    Route::post('ciudades/{ciudad}/toggle-status', [CiudadController::class, 'toggleStatus'])->name('ciudades.toggle-status');
});
Route::middleware([\App\Http\Middleware\CheckModuleStatus::class . ':sectores'])->group(function () {
    Route::resource('sectores', SectoreController::class);
});


Route::middleware([\App\Http\Middleware\CheckModuleStatus::class . ':empresas'])->group(function () {
    Route::resource('tipos-empresas', TiposEmpresaController::class);
    Route::resource('empresas', EmpresaController::class);
    Route::get('empresas/{empresa}/pdf', [EmpresaController::class, 'pdf'])->name('empresas.pdf');
});
// API de ciudades - SOLUCIÓN SIMPLE
Route::get('ciudades-api', function() {
    $departamentoId = request()->get('departamentoId', '11');

    $ciudades = [
        ['id_municipio' => 1, 'municipio' => 'Bogotá'],
        ['id_municipio' => 2, 'municipio' => 'Medellín'],
        ['id_municipio' => 3, 'municipio' => 'Cali'],
        ['id_municipio' => 4, 'municipio' => 'Barranquilla'],
        ['id_municipio' => 5, 'municipio' => 'Cartagena'],
        ['id_municipio' => 6, 'municipio' => 'Bucaramanga'],
        ['id_municipio' => 7, 'municipio' => 'Pereira'],
        ['id_municipio' => 8, 'municipio' => 'Santa Marta'],
        ['id_municipio' => 9, 'municipio' => 'Ibagué'],
        ['id_municipio' => 10, 'municipio' => 'Manizales'],
        ['id_municipio' => 11, 'municipio' => 'Villavicencio'],
        ['id_municipio' => 12, 'municipio' => 'Armenia'],
        ['id_municipio' => 13, 'municipio' => 'Valledupar'],
        ['id_municipio' => 14, 'municipio' => 'Montería'],
        ['id_municipio' => 15, 'municipio' => 'Sincelejo'],
        ['id_municipio' => 16, 'municipio' => 'Neiva'],
        ['id_municipio' => 17, 'municipio' => 'Popayán'],
        ['id_municipio' => 18, 'municipio' => 'Tunja'],
        ['id_municipio' => 19, 'municipio' => 'Florencia'],
        ['id_municipio' => 20, 'municipio' => 'Yopal'],
    ];

    return response()->json([
        'success' => true,
        'departamento_id' => $departamentoId,
        'ciudades' => $ciudades,
        'message' => 'API de ciudades funcionando correctamente',
        'environment' => 'laravel-simple',
        'timestamp' => date('Y-m-d H:i:s')
    ]);
})->name('ciudades.api');
Route::get('empresas/{empresa}/pdf', [EmpresaController::class, 'pdf'])->name('empresas.pdf');


Route::middleware([\App\Http\Middleware\CheckModuleStatus::class . ':paths-documentos'])->group(function () {
    Route::resource('paths-documentos', PathDocumentoController::class);
    Route::get('/paths-documentos', [PathDocumentoController::class, 'index'])->name('paths-documentos.index');
    Route::get('/paths-documentos/create', [PathDocumentoController::class, 'create'])->name('paths-documentos.create');
    Route::post('/paths-documentos', [PathDocumentoController::class, 'store'])->name('paths-documentos.store');
    Route::post('paths-documentos/{pathDocumento}/toggle-status', [PathDocumentoController::class, 'toggleStatus'])->name('paths-documentos.toggle-status');
});

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
    
    // Árbol Genealógico
    Route::middleware([\App\Http\Middleware\CheckModuleStatus::class . ':arbol-genealogico'])->group(function () {
        Route::get('/arbol-genealogico', [\App\Http\Controllers\ArbolGenealogicoController::class, 'index'])->name('arbol_genealogico');
    });
});

// Rutas para paseadores
Route::middleware(['auth'])->prefix('paseador')->name('paseador.')->group(function () {
    Route::get('/dashboard', [PaseadorController::class, 'login_Paseador'])->name('dashboard');
    Route::get('/perfil', [PaseadorController::class, 'index'])->name('perfil.index');
    Route::get('/perfil/{user}', [PaseadorController::class, 'show'])->name('perfil.show');
    Route::get('/perfil/{user}/edit', [PaseadorController::class, 'edit'])->name('perfil.edit');
    Route::put('/perfil/{user}', [PaseadorController::class, 'update'])->name('perfil.update');
});

// Rutas para configuraciones del sistema (solo superadmin)
Route::middleware(['auth','verified'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [SuperadminController::class, 'index'])->name('dashboard');
    Route::get('/users/edit', [SuperadminController::class, 'edit'])->name('users.edit');
    Route::get('/users/show', [SuperadminController::class, 'show'])->name('users.show');
    Route::post('/users/change-password', [SuperadminController::class, 'changePassword'])->name('users.change-password');
    Route::post('/users/{user}/toggle-status', [SuperadminController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::put('/users/{user}', [SuperadminController::class, 'update'])->name('users.update');

    // Rutas para gestión de usuarios (usuarios)
    Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/create', [UserController::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios', [UserController::class, 'store'])->name('usuarios.store');
    Route::get('/usuarios/{user}', [UserController::class, 'show'])->name('usuarios.show');
    Route::get('/usuarios/{user}/edit', [UserController::class, 'edit'])->name('usuarios.edit');
    Route::put('/usuarios/{user}', [UserController::class, 'update'])->name('usuarios.update');
    Route::delete('/usuarios/{user}', [UserController::class, 'destroy'])->name('usuarios.destroy');

    // Rutas para gestión de Proveedores OAuth
    Route::middleware([\App\Http\Middleware\CheckModuleStatus::class . ':oauth-providers'])->group(function () {
        Route::resource('oauth-providers', \App\Http\Controllers\Superadmin\OAuthProviderController::class);
        Route::patch('/oauth-providers/{oauthProvider}/toggle-status', [\App\Http\Controllers\Superadmin\OAuthProviderController::class, 'toggleStatus'])->name('oauth-providers.toggle-status');
        Route::get('/oauth-providers/{oauthProvider}/test', [\App\Http\Controllers\Superadmin\OAuthProviderController::class, 'test'])->name('oauth-providers.test');
        Route::get('/oauth-providers/{oauthProvider}/simulate', [\App\Http\Controllers\Superadmin\OAuthProviderController::class, 'simulateConnection'])->name('oauth-providers.simulate');
        Route::get('/oauth-providers/{oauthProvider}/visual-simulator', [\App\Http\Controllers\Superadmin\OAuthProviderController::class, 'visualSimulator'])->name('oauth-providers.visual-simulator');
        Route::get('/oauth-providers/test-results/{sessionId}', [\App\Http\Controllers\Superadmin\OAuthProviderController::class, 'testResults'])->name('oauth-providers.test-results');
    });

    // Rutas para configuraciones del sistema
    Route::middleware([\App\Http\Middleware\CheckModuleStatus::class . ':configuracion'])->group(function () {
        Route::get('/configuraciones', [ConfiguracionController::class, 'index'])->name('configuraciones.index');
        Route::get('/configuraciones/{id}/edit', [ConfiguracionController::class, 'edit'])->name('configuraciones.edit');
        Route::put('/configuraciones/{id}', [ConfiguracionController::class, 'update'])->name('configuraciones.update');
        Route::post('/configuraciones/session-timeout', [ConfiguracionController::class, 'updateSessionTimeout'])->name('configuraciones.update-session-timeout');
    });

    // Rutas para configuración de Base de Datos
    Route::middleware([\App\Http\Middleware\CheckModuleStatus::class . ':database-config'])->group(function () {
        Route::resource('database-configs', \App\Http\Controllers\Superadmin\DatabaseConfigController::class);
        Route::patch('/database-configs/{databaseConfig}/toggle-status', [\App\Http\Controllers\Superadmin\DatabaseConfigController::class, 'toggleStatus'])->name('database-configs.toggle-status');
        Route::get('/database-configs/{databaseConfig}/test', [\App\Http\Controllers\Superadmin\DatabaseConfigController::class, 'test'])->name('database-configs.test');
    });

    // Rutas para configuración de Correo Electrónico
    Route::middleware([\App\Http\Middleware\CheckModuleStatus::class . ':email-config'])->group(function () {
        Route::resource('email-configs', \App\Http\Controllers\Superadmin\EmailConfigController::class);
        Route::patch('/email-configs/{emailConfig}/toggle-status', [\App\Http\Controllers\Superadmin\EmailConfigController::class, 'toggleStatus'])->name('email-configs.toggle-status');
        Route::post('/email-configs/{emailConfig}/test', [\App\Http\Controllers\Superadmin\EmailConfigController::class, 'test'])->name('email-configs.test');
    });

    // Rutas para configuración de Backup de Base de Datos
    Route::middleware([\App\Http\Middleware\CheckModuleStatus::class . ':backup-config'])->group(function () {
        Route::resource('backup-configs', \App\Http\Controllers\Superadmin\BackupConfigController::class);
        Route::post('/backup-configs/{backupConfig}/execute', [\App\Http\Controllers\Superadmin\BackupConfigController::class, 'execute'])->name('backup-configs.execute');
        Route::get('/backup-configs/{backupConfig}/logs', [\App\Http\Controllers\Superadmin\BackupConfigController::class, 'logs'])->name('backup-configs.logs');
    });

    // Rutas para gestión de migraciones (acceso directo como seeders)
    Route::get('/migrations', [MigrationController::class, 'index'])->name('migrations.index');
    Route::post('/migrations/execute', [MigrationController::class, 'execute'])
        ->middleware('throttle:2,1') // 2 intentos por minuto para migraciones
        ->name('migrations.execute');

    // Rutas para AutoClean - Limpieza del Sistema
    Route::middleware([\App\Http\Middleware\CheckModuleStatus::class . ':clean'])->group(function () {
        Route::get('/clean', [CleanController::class, 'index'])->name('clean.index');
        Route::post('/clean/execute', [CleanController::class, 'execute'])
            ->middleware('throttle:2,1') // 2 intentos por minuto para limpieza
            ->name('clean.execute');
    });

    // Rutas para administración de módulos
    Route::get('/modules', [ModuleController::class, 'index'])->name('modules.index');
    Route::post('/modules/{module}/request-toggle', [ModuleController::class, 'requestToggleStatus'])
        ->middleware('throttle:5,1') // 5 intentos por minuto
        ->name('modules.request-toggle');
    Route::get('/modules/{module}/verify', [ModuleController::class, 'showVerificationForm'])->name('modules.verify');
    Route::post('/modules/{module}/confirm', [ModuleController::class, 'confirmToggleStatus'])
        ->middleware('throttle:3,1') // 3 intentos por minuto para verificación
        ->name('modules.confirm');
    Route::get('/modules/{module}/logs', [ModuleController::class, 'showLogs'])->name('modules.logs');
    Route::get('/modules-logs', [ModuleController::class, 'showAllLogs'])->name('modules.all-logs');

    // Módulo: Gestión de Seeders (acceso directo como migraciones)
    Route::get('/seeders', [SeederController::class, 'index'])->name('seeders.index');
    Route::post('/seeders/execute', [SeederController::class, 'execute'])
        ->middleware('throttle:1,1')
        ->name('seeders.execute');
});

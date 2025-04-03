<?php

use App\Http\Controllers\DatosAhorroController;
use App\Http\Controllers\EstadosCivileController;
use App\Http\Controllers\GeneroController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MeseController;
use App\Http\Controllers\MetodosAhorroController;
use App\Http\Controllers\PorcentajesAhorroController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::get('/oauth/google', function () {
    return Socialite::driver('google')->redirect();
})->name('google.login');

Route::get('/oauth/google/callback', function () {
    $googleUser = Socialite::driver('google')->stateless()->user();


    $user = User::updateOrCreate([
        'email' => $googleUser->getEmail(),
    ], [
        'name' => $googleUser->getName(),
        'google_id' => $googleUser->getId(),
        'password' => bcrypt(uniqid()), // Genera una contraseña aleatoria
    ]);

    Auth::login($user);

    return redirect('/dashboard'); // Redirige al dashboard de Jetstream
});
Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::resource('estados-civiles', EstadosCivileController::class);
    Route::resource('generos', GeneroController::class);
    Route::resource('users', UserController::class);
    Route::resource('metodos-ahorros', MetodosAhorroController::class);
    Route::resource('porcentajes-ahorros', PorcentajesAhorroController::class);
    Route::resource('datos-ahorros', DatosAhorroController::class);
    Route::resource('meses', MeseController::class);
});
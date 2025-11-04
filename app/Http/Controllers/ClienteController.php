<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\ClienteRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\MensajeDeBienvenida;
use App\Models\TipoDocumento;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Services\ClienteDataVerificationService;
use App\Services\GeocodingService;
use Illuminate\Support\Facades\Log;

class ClienteController extends Controller
{
    public function login_Cliente()
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors(['message' => 'Debes iniciar sesión para acceder.']);
        }

        $user = Auth::user();

        // Verificar si el usuario tiene el rol de cliente
        if (!$user->hasRole('Cliente')) {
            Auth::logout();
            session()->invalidate();
            return redirect()->route('logout')->withErrors(['message' => 'No tienes permisos para acceder.']);
        }

        // Verificar datos faltantes usando el servicio
        $verificationService = new ClienteDataVerificationService();
        $missingData = $verificationService->getMissingData($user);
        $completionPercentage = $verificationService->getCompletionPercentage($user);
        
        // Si el email no está verificado, OBLIGAR a verificarlo
        if (!$user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice')
                ->with('warning', 'Debes verificar tu correo electrónico antes de continuar.');
        }

        // Si hay datos faltantes críticos, mostrar vista de verificación
        if (!empty($missingData)) {
            // Buscar el mensaje de bienvenida para el rol Cliente
            $mensajeDeBienvenida = MensajeDeBienvenida::where('rol', 'Cliente')->first();
            
            return view('cliente.verificacion-datos', [
                'user' => $user,
                'missingData' => $missingData,
                'completionPercentage' => $completionPercentage,
                'titulo' => $mensajeDeBienvenida->titulo ?? 'Bienvenido a ModuStackPet',
                'descripcion' => $mensajeDeBienvenida->descripcion ?? 'Gestiona tus mascotas de manera fácil y rápida.',
                'logo' => $mensajeDeBienvenida->logo ?? 'storage/img/logo.jpg',
            ]);
        }

        // Si todo está completo, mostrar dashboard normal
        $mensajeDeBienvenida = MensajeDeBienvenida::where('rol', 'Cliente')->first();

        // Si no se encuentra un mensaje de bienvenida, usar valores por defecto
        if (!$mensajeDeBienvenida) {
            return view('cliente.dashboard', [
                'user' => $user,
                'titulo' => 'Bienvenido a ModuStackPet',
                'descripcion' => 'Gestiona tus mascotas de manera fácil y rápida.',
                'logo' => 'storage/img/logo.jpg',
            ]);
        }

        // Retornar la vista del dashboard con el mensaje de bienvenida
        return view('cliente.dashboard', [
            'user' => $user,
            'titulo' => $mensajeDeBienvenida->titulo,
            'descripcion' => $mensajeDeBienvenida->descripcion,
            'logo' => $mensajeDeBienvenida->logo ?? 'storage/img/logo.jpg',
        ]);
    }

    public function index()
    {
        $user = Auth::user();
        return view('user.cliente.index', compact('user'));
    }

    public function show(User $user)
    {
        // Verificar que el usuario solo pueda ver su propio perfil
        if (Auth::id() !== $user->id) {
            return redirect()->route('cliente.dashboard')
                ->with('error', 'No tienes permiso para ver este perfil.');
        }

        return view('user.cliente.show', compact('user'));
    }

    public function edit(User $user)
    {
        // Verificar que el usuario solo pueda editar su propio perfil
        if (Auth::id() !== $user->id) {
            return redirect()->route('cliente.dashboard')
                ->with('error', 'No tienes permiso para editar este perfil.');
        }

        $tiposDocumento = TipoDocumento::all();
        return view('user.cliente.edit', compact('user', 'tiposDocumento'));
    }

    public function update(ClienteRequest $request, User $user)
    {
        // Verificar que el usuario solo pueda actualizar su propio perfil
        if (Auth::id() !== $user->id) {
            return redirect()->route('cliente.dashboard')
                ->with('error', 'No tienes permiso para actualizar este perfil.');
        }

        // Actualizar datos del usuario
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'tipo_documento' => $request->tipo_documento,
            'cedula' => $request->cedula,
            'telefono' => $request->telefono,
            'whatsapp' => $request->whatsapp,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'activo' => $request->activo ?? $user->activo
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password)
            ]);
        }

        if ($request->hasFile('avatar')) {
            // Eliminar avatar anterior si existe
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $avatarPath = $request->file('avatar')->store('avatars/' . $user->id, 'public');
            $user->avatar = $avatarPath;
            $user->save();
        }

        // Actualizar o crear perfil de cliente
        $cliente = $user->cliente;
        if (!$cliente) {
            $cliente = \App\Models\Cliente::create([
                'user_id' => $user->id,
                'nombre' => $request->name,
            ]);
        }

        // Datos base para actualizar
        // Convertir strings vacíos a null para evitar problemas con la base de datos
        $datosCliente = [
            'nombre' => $request->name,
            'tipo_documento_id' => !empty($request->tipo_documento) ? $request->tipo_documento : null,
            'cedula' => !empty($request->cedula) ? $request->cedula : null,
            'telefono' => !empty($request->telefono) ? $request->telefono : null,
            'whatsapp' => !empty($request->whatsapp) ? $request->whatsapp : null,
            'fecha_nacimiento' => !empty($request->fecha_nacimiento) ? $request->fecha_nacimiento : null,
            'direccion' => !empty($request->direccion) ? $request->direccion : null,
            'ciudad_id' => !empty($request->ciudad_id) ? $request->ciudad_id : null,
            'barrio_id' => !empty($request->barrio_id) ? $request->barrio_id : null,
        ];

        // Verificar si la dirección cambió para geocodificar nuevamente
        $direccionCambio = $request->filled('direccion') && 
                          ($cliente->direccion !== $request->direccion || 
                           !$cliente->latitud || !$cliente->longitud);

        // Geocodificar dirección si cambió o si no tiene coordenadas
        if ($direccionCambio) {
            try {
                $geocodingService = new GeocodingService();

                // Geocodificar dirección (específica para Engativá, Bogotá)
                $coordenadas = $geocodingService->geocodeEngativa($request->direccion);
                
                if ($coordenadas) {
                    $datosCliente['latitud'] = $coordenadas['latitud'];
                    $datosCliente['longitud'] = $coordenadas['longitud'];
                    
                    Log::info('Dirección geocodificada exitosamente', [
                        'user_id' => $user->id,
                        'direccion' => $request->direccion,
                        'latitud' => $coordenadas['latitud'],
                        'longitud' => $coordenadas['longitud'],
                    ]);
                } else {
                    Log::warning('No se pudo geocodificar la dirección', [
                        'user_id' => $user->id,
                        'direccion' => $request->direccion,
                    ]);
                    // Si no se puede geocodificar y no hay coordenadas previas, mantener null
                    if (!$cliente->latitud && !$cliente->longitud) {
                        $datosCliente['latitud'] = null;
                        $datosCliente['longitud'] = null;
                    }
                }
            } catch (\Exception $e) {
                Log::error('Error al geocodificar dirección', [
                    'user_id' => $user->id,
                    'direccion' => $request->direccion,
                    'error' => $e->getMessage(),
                ]);
                // Si hay error y no hay coordenadas previas, mantener null
                if (!$cliente->latitud && !$cliente->longitud) {
                    $datosCliente['latitud'] = null;
                    $datosCliente['longitud'] = null;
                }
            }
        } else {
            // Si no cambió la dirección, mantener las coordenadas existentes
            if ($cliente->latitud && $cliente->longitud) {
                $datosCliente['latitud'] = $cliente->latitud;
                $datosCliente['longitud'] = $cliente->longitud;
            }
        }

        // Manejar avatar del cliente
        if ($request->hasFile('avatar')) {
            // Eliminar avatar anterior del cliente si existe
            if ($cliente->avatar && $cliente->avatar !== $user->avatar) {
                // Solo eliminar si es diferente al del usuario
                if (file_exists(public_path('storage/' . $cliente->avatar))) {
                    Storage::disk('public')->delete($cliente->avatar);
                } elseif (file_exists(public_path($cliente->avatar))) {
                    @unlink(public_path($cliente->avatar));
                }
            }

            // Usar el mismo avatar que el usuario (ya se guardó arriba)
            $datosCliente['avatar'] = $user->avatar;
        } elseif ($cliente->avatar && !$user->avatar) {
            // Si el usuario no tiene avatar pero el cliente sí, mantener el del cliente
            // No hacer nada, mantener el avatar actual del cliente
        } elseif ($user->avatar) {
            // Si el usuario tiene avatar, sincronizar con el cliente
            $datosCliente['avatar'] = $user->avatar;
        }

        // Actualizar el cliente con todos los datos
        $cliente->update($datosCliente);

        // Log para debugging
        Log::info('Datos del cliente actualizados', [
            'user_id' => $user->id,
            'cliente_id' => $cliente->id,
            'datos_guardados' => [
                'barrio_id' => $datosCliente['barrio_id'],
                'ciudad_id' => $datosCliente['ciudad_id'],
                'latitud' => $datosCliente['latitud'] ?? 'no establecida',
                'longitud' => $datosCliente['longitud'] ?? 'no establecida',
                'direccion' => $datosCliente['direccion'],
            ],
        ]);

        // Verificar que se guardaron correctamente
        $cliente->refresh();
        
        return redirect()->route('cliente.dashboard')
            ->with('success', 'Perfil actualizado exitosamente.');
    }

    /**
     * Mostrar una lista de los recursos.
     */
    public function index_admin(Request $request): View
    {
        $users = User::paginate();

        return view('user.index', compact('users'))
            ->with('i', ($request->input('page', 1) - 1) * $users->perPage());
    }

    /**
     * Mostrar el formulario para crear un nuevo recurso.
     */
    public function create(): View
    {
        $user = new User();
        $tiposDocumento = TipoDocumento::all(); // Obtener todos los tipos de documento

        return view('user.create', compact('user', 'tiposDocumento')); // Pasar los datos a la vista
    }

    /**
     * Almacenar un recurso recién creado en el almacenamiento.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'tipo_documento' => 'required|exists:tipo_documentos,id',
            'cedula' => 'required|numeric|digits_between:6,12|unique:users,cedula', // Validación de cédula
            'fecha_nacimiento' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $age = \Carbon\Carbon::parse($value)->age;
                    if ($age < 18) {
                        $fail('Debe tener al menos 18 años.');
                    }
                },
            ],
            'telefono' => 'nullable|string|max:15',
            'whatsapp' => 'nullable|string|max:15',
            'password' => 'nullable|string|min:8|confirmed',
            'activo' => 'boolean',
            'avatar' => 'nullable|image|max:2048', // Validar que sea una imagen
        ]);

        // Manejar la subida de la imagen
        if ($request->hasFile('avatar')) {
            $cedula = $validatedData['cedula'];
            $avatarPath = 'avatars/' . $cedula;
            $avatarName = $cedula . '.' . $request->file('avatar')->getClientOriginalExtension();
            $request->file('avatar')->move(public_path($avatarPath), $avatarName);
            $validatedData['avatar'] = $avatarPath . '/' . $avatarName;
        }

        // Crear el usuario con los datos validados
        $validatedData['password'] = bcrypt($validatedData['password']); // Encriptar la contraseña
        User::create($validatedData);

        return Redirect::route('users.index')
            ->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Mostrar el recurso especificado.
     */
    public function show_admin($id): View
    {
        $user = User::find($id);

        return view('user.show', compact('user'));
    }

    /**
     * Mostrar el formulario para editar el recurso especificado.
     */
    public function edit_admin($id): View
    {
        $user = User::findOrFail($id); // Obtener el usuario por ID
        $tiposDocumento = TipoDocumento::all(); // Obtener todos los tipos de documento

        return view('user.edit', compact('user', 'tiposDocumento')); // Pasar los datos a la vista
    }

    /**
     * Actualizar el recurso especificado en el almacenamiento.
     */
    public function update_admin(Request $request, User $user): RedirectResponse
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id, // Ignorar el email del usuario actual
            'tipo_documento' => 'required|exists:tipo_documentos,id',
            'cedula' => 'required|numeric|digits_between:6,12|unique:users,cedula,' . $user->id, // Validación de cédula
            'fecha_nacimiento' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $age = \Carbon\Carbon::parse($value)->age;
                    if ($age < 18) {
                        $fail('Debe tener al menos 18 años.');
                    }
                },
            ],
            'telefono' => 'nullable|string|max:15',
            'whatsapp' => 'nullable|string|max:15',
            'password' => 'nullable|string|min:8|confirmed',
            'activo' => 'boolean',
            'avatar' => 'nullable|image|max:2048', // Validar que sea una imagen
        ]);

        // Manejar la subida de la imagen
        if ($request->hasFile('avatar')) {
            $cedula = $validatedData['cedula'];
            $avatarPath = 'avatars/' . $cedula;
            $avatarName = $cedula . '.' . $request->file('avatar')->getClientOriginalExtension();

            // Eliminar la imagen anterior si existe
            if ($user->avatar && file_exists(public_path($user->avatar))) {
                unlink(public_path($user->avatar));
            }

            $request->file('avatar')->move(public_path($avatarPath), $avatarName);
            $validatedData['avatar'] = $avatarPath . '/' . $avatarName;
        }

        // Encriptar la contraseña si se proporciona
        if (!empty($validatedData['password'])) {
            $validatedData['password'] = bcrypt($validatedData['password']);
        } else {
            unset($validatedData['password']); // No actualizar la contraseña si no se proporciona
        }

        // Actualizar el usuario con los datos validados
        $user->update($validatedData);

        return Redirect::route('users.index')
            ->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Eliminar el recurso especificado del almacenamiento.
     */
    public function destroy($id): RedirectResponse
    {
        User::find($id)->delete();

        return Redirect::route('users.index')
            ->with('success', 'Usuario eliminado exitosamente.');
    }
}

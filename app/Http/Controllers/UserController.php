<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Paseador;
use App\Models\TipoDocumento;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\View\View;

use App\Http\Controllers\Controller; // Asegúrate de que este namespace esté presente

class UserController extends Controller
{
    /* public function __construct()
    {
        $this->middleware('auth'); // Asegura que solo usuarios autenticados puedan acceder
    } */

    /**
     * Mostrar una lista de los recursos.
     */
    public function index(Request $request): View
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

        return view('user.superadmin.create', compact('user', 'tiposDocumento')); // Pasar los datos a la vista
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
            'password' => 'nullable|string|min:8',
            'activo' => 'boolean',
            'avatar' => 'nullable|image|max:2048', // Validar que sea una imagen
        ]);

        // Generar password si no se proporciona
        $password = $validatedData['password'] ?? Str::random(12);

        // Manejar la subida de la imagen
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $cedula = $validatedData['cedula'];
            $avatarPath = 'avatars/' . $cedula;
            $avatarName = $cedula . '.' . $request->file('avatar')->getClientOriginalExtension();
            $request->file('avatar')->move(public_path($avatarPath), $avatarName);
            $avatarPath = $avatarPath . '/' . $avatarName;
        }

        // Crear el usuario con los datos validados
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($password),
            'tipo_documento' => $validatedData['tipo_documento'],
            'cedula' => $validatedData['cedula'],
            'fecha_nacimiento' => $validatedData['fecha_nacimiento'],
            'telefono' => $validatedData['telefono'] ?? null,
            'whatsapp' => $validatedData['whatsapp'] ?? null,
            'activo' => $validatedData['activo'] ?? true,
            'avatar' => $avatarPath,
        ]);

        // Asignar rol "Paseador" automáticamente
        $user->assignRole('Paseador');

        // Crear perfil en tabla paseadores
        Paseador::create([
            'user_id' => $user->id,
            'tipo_documento_id' => $validatedData['tipo_documento'],
            'cedula' => $validatedData['cedula'],
            'telefono' => $validatedData['telefono'] ?? null,
            'whatsapp' => $validatedData['whatsapp'] ?? null,
            'fecha_nacimiento' => $validatedData['fecha_nacimiento'],
            'disponibilidad' => true,
            'avatar' => $avatarPath,
        ]);

        // Enviar email con credenciales
        try {
            $user->notify(new \App\Notifications\CredencialesPaseadorNotification(
                $password,
                $validatedData['email']
            ));
        } catch (\Exception $e) {
            \Log::warning('Error al enviar email de credenciales al paseador', [
                'user_id' => $user->id,
                'email' => $validatedData['email'],
                'error' => $e->getMessage(),
            ]);
            // No interrumpir el flujo si falla el email
        }

        return Redirect::route('superadmin.usuarios.index')
            ->with('success', 'Paseador creado exitosamente. ' . 
                ($request->has('password') ? 'Credenciales enviadas por email.' : 'Se generó una contraseña automáticamente y se envió por email.'));
    }

    /**
     * Mostrar el recurso especificado.
     */
    public function show($id): View
    {
        $user = User::find($id);

        return view('user.show', compact('user'));
    }

    /**
     * Mostrar el formulario para editar el recurso especificado.
     */
    public function edit($id): View
    {
        $user = User::findOrFail($id); // Obtener el usuario por ID
        $tiposDocumento = TipoDocumento::all(); // Obtener todos los tipos de documento

        return view('user.edit', compact('user', 'tiposDocumento')); // Pasar los datos a la vista
    }

    /**
     * Actualizar el recurso especificado en el almacenamiento.
     */
    public function update(Request $request, User $user): RedirectResponse
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

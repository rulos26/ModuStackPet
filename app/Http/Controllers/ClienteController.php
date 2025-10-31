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

        return view('cliente.dashboard', compact('user'));
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

        return view('user.cliente.edit', compact('user'));
    }

    public function update(ClienteRequest $request, User $user)
    {
        // Verificar que el usuario solo pueda actualizar su propio perfil
        if (Auth::id() !== $user->id) {
            return redirect()->route('cliente.dashboard')
                ->with('error', 'No tienes permiso para actualizar este perfil.');
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'active' => $request->active ?? $user->active
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

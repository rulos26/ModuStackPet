<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\TipoDocumento;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Redirect;
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
            'cedula' => 'required|numeric|unique:users,cedula',
            'fecha_nacimiento' => 'nullable|date',
            'telefono' => 'nullable|string|max:15',
            'whatsapp' => 'nullable|string|max:15',
            'password' => 'nullable|string|min:8|confirmed',
            'activo' => 'boolean',
            'avatar' => 'nullable|image|max:2048', // Validar que sea una imagen
        ]);

        // Manejar la subida de la imagen
        if ($request->hasFile('avatar')) {
            // Crear una carpeta específica para el usuario basada en su cédula
            $cedula = $validatedData['cedula'];
            $avatarPath = 'avatars/' . $cedula;

            // Crear el nombre del archivo basado en la cédula
            $avatarName = $cedula . '.' . $request->file('avatar')->getClientOriginalExtension();

            // Mover la imagen a la carpeta pública con el nombre generado
            $request->file('avatar')->move(public_path($avatarPath), $avatarName);

            // Guardar la ruta relativa en la base de datos
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
        dd($user, $request->all());
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id, // Ignorar el email del usuario actual
            'tipo_documento' => 'required|exists:tipo_documentos,id',
            'cedula' => 'required|numeric|unique:users,cedula,' . $user->id, // Ignorar la cédula del usuario actual
            'fecha_nacimiento' => 'nullable|date',
            'telefono' => 'nullable|string|max:15',
            'whatsapp' => 'nullable|string|max:15',
            'password' => 'nullable|string|min:8|confirmed',
            'activo' => 'boolean',
            'avatar' => 'nullable|image|max:2048', // Validar que sea una imagen
        ]);

        // Manejar la subida de la imagen
        if ($request->hasFile('avatar')) {
            // Crear una carpeta específica para el usuario basada en su cédula
            $cedula = $validatedData['cedula'];
            $avatarPath = 'avatars/' . $cedula;

            // Crear el nombre del archivo basado en la cédula
            $avatarName = $cedula . '.' . $request->file('avatar')->getClientOriginalExtension();

            // Eliminar la imagen anterior si existe
            if ($user->avatar && file_exists(public_path($user->avatar))) {
                unlink(public_path($user->avatar));
            }

            // Mover la nueva imagen a la carpeta pública con el nombre generado
            $request->file('avatar')->move(public_path($avatarPath), $avatarName);

            // Guardar la ruta relativa en la base de datos
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

<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\TipoDocumento;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\Controller; // Asegúrate de que este namespace esté presente

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostrar una lista de los recursos.
     */
    public function index(Request $request): View
    {
        $role = auth()->user()->roles->first();

        switch($role->name) {
            case 'Superadmin':
                return $this->superadminIndex();
            case 'Admin':
                return $this->adminIndex();
            case 'Cliente':
                return $this->clienteIndex();
            case 'Paseador':
                return $this->paseadorIndex();
            default:
                abort(403);
        }
    }

    /**
     * Mostrar el formulario para crear un nuevo recurso.
     */
    public function create(): View
    {
        $role = auth()->user()->roles->first();

        switch($role->name) {
            case 'Superadmin':
                return $this->superadminCreate();
            case 'Admin':
                return $this->adminCreate();
            case 'Cliente':
                return $this->clienteCreate();
            case 'Paseador':
                return $this->paseadorCreate();
            default:
                abort(403);
        }
    }

    /**
     * Almacenar un recurso recién creado en el almacenamiento.
     */
    public function store(Request $request): RedirectResponse
    {
        $role = auth()->user()->roles->first();

        switch($role->name) {
            case 'Superadmin':
                return $this->superadminStore($request);
            case 'Admin':
                return $this->adminStore($request);
            case 'Cliente':
                return $this->clienteStore($request);
            case 'Paseador':
                return $this->paseadorStore($request);
            default:
                abort(403);
        }
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

    // Métodos específicos para Superadmin
    protected function superadminIndex()
    {
        $users = User::with('roles')->get();
        return view('user.superadmin.index', compact('users'));
    }

    protected function superadminCreate()
    {
        $roles = Role::all();
        return view('user.superadmin.create', compact('roles'));
    }

    protected function superadminStore(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'role' => 'required|exists:roles,name',
            'avatar' => 'nullable|image|max:2048'
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars/' . $request->cedula . '/users', 'public');
            $user->avatar = $path;
        }

        $user->save();
        $user->assignRole($request->role);

        return redirect()->route('users.index')
            ->with('success', 'Usuario creado exitosamente');
    }

    // Métodos específicos para Admin
    protected function adminIndex()
    {
        $users = User::role(['Cliente', 'Paseador'])->get();
        return view('user.admin.index', compact('users'));
    }

    protected function adminCreate()
    {
        $roles = Role::whereIn('name', ['Cliente', 'Paseador'])->get();
        return view('user.admin.create', compact('roles'));
    }

    protected function adminStore(Request $request)
    {
        // Similar a superadminStore pero con restricciones
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'role' => 'required|in:Cliente,Paseador',
            'avatar' => 'nullable|image|max:2048'
        ]);

        // ... resto del código similar a superadminStore
    }

    // Métodos específicos para Cliente
    protected function clienteIndex()
    {
        $users = User::where('id', auth()->id())->get();
        return view('user.cliente.index', compact('users'));
    }

    protected function clienteCreate()
    {
        return view('user.cliente.create');
    }

    protected function clienteStore(Request $request)
    {
        // Lógica específica para crear usuarios cliente
    }

    // Métodos específicos para Paseador
    protected function paseadorIndex()
    {
        $users = User::where('id', auth()->id())->get();
        return view('user.paseador.index', compact('users'));
    }

    protected function paseadorCreate()
    {
        return view('user.paseador.create');
    }

    protected function paseadorStore(Request $request)
    {
        // Lógica específica para crear usuarios paseador
    }

    // Métodos compartidos
    protected function handleAvatarUpload($request, $user)
    {
        if ($request->hasFile('avatar')) {
            // Eliminar avatar anterior si existe
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $path = $request->file('avatar')->store('avatars/' . $user->cedula . '/users', 'public');
            return $path;
        }
        return null;
    }
}

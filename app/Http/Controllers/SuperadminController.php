<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\MensajeDeBienvenida;
use App\Models\User;
use App\Models\TipoDocumento;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use App\Http\Requests\SuperadminRequest;
use App\Models\Empresa;

class SuperadminController extends Controller
{


    public function login_Superadmin()
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors(['message' => 'Debes iniciar sesión para acceder.']);
        }

        $user = Auth::user(); // Obtener el usuario autenticado
        $roles = $user->roles->pluck('name'); // Obtener los roles del usuario

        // Verificar si el usuario tiene al menos un rol asignado
        if ($roles->isEmpty()) {
            Auth::logout(); // Cerrar sesión
            session()->invalidate(); // Invalidar la sesión
            return redirect()->route('logout')->withErrors(['message' => 'No tienes permisos para acceder.']);
        }

        // Buscar el mensaje de bienvenida que coincida con el rol del usuario
        $mensajeDeBienvenida = MensajeDeBienvenida::where('rol', $roles->first())->first();

        // Verificar si se encontró un mensaje de bienvenida
        if (!$mensajeDeBienvenida) {
            return redirect()->route('dashboard')->withErrors(['message' => 'No se encontró un mensaje de bienvenida para tu rol.']);
        }

        // Retornar la vista del dashboard con los roles y el mensaje de bienvenida
        return view('superadmin.dashboard', [
            'roles' => $roles,
            'titulo' => $mensajeDeBienvenida->titulo,
            'descripcion' => $mensajeDeBienvenida->descripcion,
            'logo' => $mensajeDeBienvenida->logo,
        ]);
    }

    /**
     * Mostrar una lista de los recursos.
     */
    public function index()
    {
        $users = User::with('roles')->paginate(10);
        $roles = Role::all();
        return view('user.superadmin.index', compact('users', 'roles'));
    }

    /**
     * Mostrar el formulario para crear un nuevo recurso.
     */
    public function create()
    {
        $roles = Role::all();
        return view('user.superadmin.create', compact('roles'));
    }

    /**
     * Almacenar un recurso recién creado en el almacenamiento.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'required|array',
            'active' => 'required|boolean',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'active' => $request->active
        ]);

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars/' . $user->id, 'public');
            $user->avatar = $avatarPath;
            $user->save();
        }

        $user->syncRoles($request->roles);

        return redirect()->route('superadmin.users.index')
            ->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Mostrar el recurso especificado.
     */
    public function show()
    {
        $user = User::role('Superadmin')->first();
        return view('user.superadmin.show', compact('user'));
    }

    /**
     * Mostrar el formulario para editar el recurso especificado.
     */
    public function edit()
    {
        $user = User::role('Superadmin')->first();
        $roles = Role::all();
        return view('user.superadmin.edit', compact('user', 'roles'));
    }

    /**
     * Actualizar el recurso especificado en el almacenamiento.
     */
    public function update(Request $request, User $user)
    {

          $empresa = Empresa::all();
          $empresa = $empresa->first();
          dd($request->all(),$user,$empresa,$empresa->nombre_legal);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'required|array',
            'active' => 'required|boolean',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'active' => $request->active
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

        $user->syncRoles($request->roles);

        return redirect()->route('superadmin.users.index')
            ->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Eliminar el recurso especificado del almacenamiento.
     */
    public function destroy(User $user)
    {
        // No permitir eliminar al propio superadmin
        if ($user->id === Auth::id()) {
            return redirect()->route('superadmin.users.index')
                ->with('error', 'No puedes eliminar tu propio usuario.');
        }

        // Eliminar avatar si existe
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        return redirect()->route('superadmin.users.index')
            ->with('success', 'Usuario eliminado exitosamente.');
    }

    public function toggleStatus(User $user)
    {
        // No permitir desactivar al propio superadmin
        if ($user->id === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes desactivar tu propio usuario.'
            ]);
        }

        $user->update([
            'active' => !$user->active
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Estado actualizado correctamente.',
            'status' => $user->active
        ]);
    }

    /**
     * Cambiar la contraseña del superadmin.
     */
    public function changePassword(SuperadminRequest $request)
    {
        $user = auth()->user();
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->back()->with('success', 'Contraseña actualizada exitosamente.');
    }
}

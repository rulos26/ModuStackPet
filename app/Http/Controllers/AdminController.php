<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\MensajeDeBienvenida;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use App\Models\TipoDocumento;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\AdminRequest;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function login_Admin()
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
        return view('admin.dashboard', [
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
        $users = User::with('roles')->whereHas('roles', function($query) {
            $query->whereIn('name', ['admin', 'cliente', 'paseador']);
        })->get();

        return view('user.admin.index', compact('users'));
    }

    /**
     * Mostrar el formulario para crear un nuevo recurso.
     */
    public function create()
    {
        $roles = Role::whereIn('name', ['admin', 'cliente', 'paseador'])->get();
        $tiposDocumento = TipoDocumento::all();
        return view('user.admin.create', compact('roles', 'tiposDocumento'));
    }

    /**
     * Almacenar un recurso recién creado en el almacenamiento.
     */
    public function store(AdminRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'active' => $request->active ?? true
        ]);

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars/' . $user->id, 'public');
            $user->avatar = $avatarPath;
            $user->save();
        }

        $user->assignRole($request->role);

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Mostrar el recurso especificado.
     */
    public function show(User $user)
    {
        return view('user.admin.show', compact('user'));
    }

    /**
     * Mostrar el formulario para editar el recurso especificado.
     */
    public function edit(User $user)
    {
        $roles = Role::whereIn('name', ['admin', 'cliente', 'paseador'])->get();
        $tiposDocumento = TipoDocumento::all();
        return view('user.admin.edit', compact('user', 'roles', 'tiposDocumento'));
    }

    /**
     * Actualizar el recurso especificado en el almacenamiento.
     */
    public function update(AdminRequest $request, User $user)
    {
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
                \Storage::disk('public')->delete($user->avatar);
            }

            $avatarPath = $request->file('avatar')->store('avatars/' . $user->id, 'public');
            $user->avatar = $avatarPath;
            $user->save();
        }

        $user->syncRoles([$request->role]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Eliminar el recurso especificado del almacenamiento.
     */
    public function destroy(User $user)
    {
        if ($user->hasRole('superadmin')) {
            return redirect()->route('admin.users.index')
                ->with('error', 'No se puede eliminar un superadmin.');
        }

        // Eliminar avatar si existe
        if ($user->avatar) {
            \Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario eliminado exitosamente.');
    }
}

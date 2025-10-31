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
        // Si se solicita desde la ruta /superadmin/dashboard, mostrar el dashboard
        if (request()->routeIs('superadmin.dashboard')) {
            return $this->showDashboard();
        }
        
        // Si no, mostrar la lista de usuarios
        $users = User::with('roles')->paginate(10);
        $roles = Role::all();
        return view('user.superadmin.index', compact('users', 'roles'));
    }

    /**
     * Mostrar el dashboard de superadmin con acciones rápidas
     */
    private function showDashboard()
    {
        $user = Auth::user();
        $roles = $user->roles->pluck('name');

        if ($roles->isEmpty()) {
            Auth::logout();
            session()->invalidate();
            return redirect()->route('logout')->withErrors(['message' => 'No tienes permisos para acceder.']);
        }

        $mensajeDeBienvenida = MensajeDeBienvenida::where('rol', $roles->first())->first();

        if (!$mensajeDeBienvenida) {
            return redirect()->route('dashboard')->withErrors(['message' => 'No se encontró un mensaje de bienvenida para tu rol.']);
        }

        // Obtener módulos activos para acciones rápidas
        $modules = \App\Models\Module::where('status', true)
            ->whereIn('slug', ['mascotas', 'certificados', 'empresas', 'configuracion', 'migraciones', 'seeders', 'clean', 'modulos', 'reportes'])
            ->orderBy('name')
            ->get();

        return view('superadmin.dashboard', [
            'roles' => $roles,
            'titulo' => $mensajeDeBienvenida->titulo,
            'descripcion' => $mensajeDeBienvenida->descripcion,
            'logo' => $mensajeDeBienvenida->logo,
            'modules' => $modules,
        ]);
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
        $nombreEmpresa = str_replace(' ', '_', trim($empresa->nombre_legal)); // Primero reemplazamos espacios
        $nombreEmpresa = strtolower(preg_replace('/[^a-zA-Z0-9_-]/', '_', $nombreEmpresa)); // Luego limpiamos caracteres especiales
        $nombreEmpresa = preg_replace('/_+/', '_', $nombreEmpresa); // Eliminar guiones bajos múltiples
        $roles = $user->roles->pluck('name');
        $cedula_user = $user->cedula ?? $empresa->nit;
        $ruta = $nombreEmpresa.'/'.$roles[0].'/'.$cedula_user.'/imagenes/perfil';
        $rutaDB = 'public/'.$ruta;
        dd($rutaDB);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            /* 'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048' */
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'avatar' => $rutaDB,
        ]);

        if ($request->hasFile('avatar')) {
            // Obtener la extensión del archivo
            $extension = $request->file('avatar')->getClientOriginalExtension();

            // Construir el nombre del archivo usando la cédula
            $nombreArchivo = $cedula_user . '.' . $extension;

            // Ruta completa del archivo
            $rutaCompleta = $ruta . '/' . $nombreArchivo;

            // Eliminar avatar anterior si existe
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Asegurarse de que el directorio existe
            Storage::disk('public')->makeDirectory($ruta);

            // Guardar el nuevo archivo
            $avatarPath = $request->file('avatar')->storeAs($ruta, $nombreArchivo, 'public');

            // Actualizar el campo avatar en la base de datos
            $user->avatar = $avatarPath;
            $user->save();
        }

        return redirect()->route('superadmin.users.show')
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

<?php

namespace App\Http\Controllers;

use App\Models\PathDocumento;
use App\Models\Empresa;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PathDocumentoController extends Controller
{
    /**
     * Muestra la lista de paths de documentos del usuario autenticado.
     */
    public function index()
    {
        $paths = PathDocumento::all();

        return view('paths-documentos.index', compact('paths'));
    }

    /**
     * Genera y guarda automáticamente los paths de documentos para todos los usuarios.
     */
    public function create()
    {
        try {
            // Obtener todas las empresas activas
            $empresas = Empresa::activas()
                ->select(['id', 'nombre_legal', 'nombre_comercial'])
                ->orderBy('nombre_legal')
                ->get();

            Log::info('Empresas encontradas: ' . $empresas->count());

            // Obtener todos los roles del sistema
            $roles = Role::select(['id', 'name'])
                ->orderBy('name')
                ->get();

            Log::info('Roles encontrados: ' . $roles->count());

            // Obtener todos los usuarios del sistema con sus roles
            $usuarios = User::with('roles')
                ->select(['id', 'name', 'email', 'cedula'])
                ->orderBy('name')
                ->get();

            Log::info('Usuarios encontrados: ' . $usuarios->count());
            //dd($usuarios, $roles, $empresas);
            $pathsProcesados = 0;
            $totalUsuariosConRoles = 0;

            foreach ($roles as $rol) {
                $usuariosDelRol = $usuarios->filter(function($user) use ($rol) {
                    return $user->hasRole($rol->name);
                });

                Log::info("Usuarios con rol {$rol->name}: " . $usuariosDelRol->count());
                $totalUsuariosConRoles += $usuariosDelRol->count();

                foreach ($empresas as $empresa) {
                    foreach ($usuariosDelRol as $usuario) {
                        // Asignar '000000' si la cédula es null
                        $cedula = $usuario->cedula ?? '000000';

                        // Sanitizar nombres para la ruta y convertir a minúsculas
                        $nombreEmpresa = str_replace(' ', '_', trim($empresa->nombre_legal)); // Primero reemplazamos espacios
                        $nombreEmpresa = strtolower(preg_replace('/[^a-zA-Z0-9_-]/', '_', $nombreEmpresa)); // Luego limpiamos caracteres especiales
                        $nombreEmpresa = preg_replace('/_+/', '_', $nombreEmpresa); // Eliminar guiones bajos múltiples

                        $nombreRol = str_replace(' ', '_', trim($rol->name));
                        $nombreRol = strtolower(preg_replace('/[^a-zA-Z0-9_-]/', '_', $nombreRol));
                        $nombreRol = preg_replace('/_+/', '_', $nombreRol); // Eliminar guiones bajos múltiples

                        $cedula = str_replace(' ', '_', trim($cedula));
                        $cedula = strtolower(preg_replace('/[^a-zA-Z0-9_-]/', '_', $cedula));
                        $cedula = preg_replace('/_+/', '_', $cedula); // Eliminar guiones bajos múltiples

                        // Crear paths para documentos e imágenes
                        $paths = [
                            "public/{$nombreEmpresa}/{$nombreRol}/{$cedula}/documentos",
                            "public/{$nombreEmpresa}/{$nombreRol}/{$cedula}/imagenes"
                        ];

                        foreach ($paths as $path) {
                            $pathDocumento = PathDocumento::updateOrCreate(
                                [
                                    'nombre_path' => strtolower($path),
                                    'user_id' => $usuario->id
                                ],
                                [
                                    'estado' => true
                                ]
                            );
                            $pathsProcesados++;
                        }
                    }
                }
            }

            Log::info("Total de usuarios con roles: {$totalUsuariosConRoles}");
            Log::info("Total de paths procesados: {$pathsProcesados}");
            Log::info("Total de paths esperados: " . ($totalUsuariosConRoles * $empresas->count() * 2));

            return redirect()->route('paths-documentos.index')
                ->with('success', "Se han procesado {$pathsProcesados} rutas de documentos.");
        } catch (\Exception $e) {
            Log::error('Error al procesar las rutas: ' . $e->getMessage());
            return redirect()->route('paths-documentos.index')
                ->with('error', 'Error al procesar las rutas: ' . $e->getMessage());
        }
    }

    /**
     * Muestra los detalles de un path de documento específico.
     */
    public function show(PathDocumento $pathDocumento)
    {
        $this->authorize('view', $pathDocumento);
        return view('paths-documentos.show', compact('pathDocumento'));
    }

    /**
     * Muestra el formulario para editar un path de documento existente.
     */
    public function edit(PathDocumento $pathDocumento)
    {
        $this->authorize('update', $pathDocumento);
        return view('paths-documentos.edit', compact('pathDocumento'));
    }

    /**
     * Actualiza un path de documento existente en la base de datos.
     */
    public function update(Request $request, PathDocumento $pathDocumento)
    {
        $this->authorize('update', $pathDocumento);

        $request->validate([
            'nombre_path' => 'required|string|max:255',
            'estado' => 'required|boolean'
        ]);

        $pathDocumento->update($request->all());

        return redirect()->route('paths-documentos.index')
            ->with('success', 'Ruta de documento actualizada exitosamente.');
    }

    /**
     * Elimina un path de documento de la base de datos.
     */
    public function destroy(PathDocumento $pathDocumento)
    {
        $this->authorize('delete', $pathDocumento);
        $pathDocumento->delete();

        return redirect()->route('paths-documentos.index')
            ->with('success', 'Ruta de documento eliminada exitosamente.');
    }
}

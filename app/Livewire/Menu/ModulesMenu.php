<?php

namespace App\Livewire\Menu;

use App\Models\Module;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class ModulesMenu extends Component
{
    public array $items = [];

    #[On('module-status-updated')]
    public function refreshMenu(): void
    {
        $this->loadItems();
    }

    public function mount(): void
    {
        $this->loadItems();
    }

    private function loadItems(): void
    {
        $user = Auth::user();
        $modules = Module::active()->orderBy('name')->get();

        $this->items = $modules->filter(function (Module $m) use ($user) {
            // Si usas permisos por módulo, valida aquí: $user->can('ver ' . $m->slug)
            return true; // Placeholder: ya están activos; permisos específicos pueden integrarse aquí
        })->map(function (Module $m) {
            return [
                'name' => $m->name,
                'slug' => $m->slug,
                'route' => $this->guessRouteFor($m->slug),
                'icon' => 'fa-puzzle-piece',
            ];
        })->values()->all();
    }

    private function guessRouteFor(string $slug): string
    {
        $map = [
            'mascotas' => 'mascotas.index',
            'certificados' => 'vacunas_certificaciones.index',
            'reportes' => 'pdf.generar',
            'empresas' => 'empresas.index',
            'configuracion' => 'superadmin.configuraciones.index',
            'migraciones' => 'superadmin.migrations.index',
            'clean' => 'superadmin.clean.index',
            'seeders' => 'superadmin.seeders.index',
            'modulos' => 'superadmin.modules.index',
            'bienvenida' => 'mensaje-de-bienvenidas.index',
            'departamentos' => 'departamentos.index',
            'ciudades' => 'ciudades.index',
            'sectores' => 'sectores.index',
            'tipos-empresas' => 'tipos-empresas.index',
            'tipo-documentos' => 'tipo-documentos.index',
            'paths-documentos' => 'paths-documentos.index',
            'razas' => 'razas.index',
            'barrios' => 'barrios.index',
        ];
        
        // Si está en el mapa, usar la ruta específica
        if (isset($map[$slug])) {
            return $map[$slug];
        }
        
        // Para módulos nuevos auto-creados, intentar generar rutas automáticamente
        // Patrón: {slug}.index o {slug}.dashboard
        $possibleRoutes = [
            $slug . '.index',
            $slug . '.dashboard',
            str_replace('-', '_', $slug) . '.index',
            'superadmin.' . $slug . '.index',
        ];
        
        // Verificar si alguna ruta existe usando route_exists helper
        foreach ($possibleRoutes as $route) {
            try {
                if (\Illuminate\Support\Facades\Route::has($route)) {
                    return $route;
                }
            } catch (\Exception $e) {
                // Continuar con el siguiente
            }
        }
        
        // Si no se encuentra, retornar '#' (sin enlace) para evitar errores
        return '#';
    }

    public function render()
    {
        return view('livewire.menu.modules-menu');
    }
}



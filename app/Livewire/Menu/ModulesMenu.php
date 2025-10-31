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
        ];
        return $map[$slug] ?? '#';
    }

    public function render()
    {
        return view('livewire.menu.modules-menu');
    }
}



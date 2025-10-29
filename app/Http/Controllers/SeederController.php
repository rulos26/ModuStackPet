<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\ModuleLog;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class SeederController extends Controller
{
    private array $allowedSeeders = [
        // FQCN con casing estándar
        'Database\\Seeders\\ModuleSeeder',
        'Database\\Seeders\\ExecuteSqlSeeder',
        'Database\\Seeders\\UserSeeder',
        'Database\\Seeders\\RoleSeeder',
        'Database\\Seeders\\TokenSeeder',
        'Database\\Seeders\\DepartamentoSqlSeeder',
        // Variantes tolerantes por si el proyecto define clases con casing distinto
        'Database\\Seeders\\roleSeeder',
    ];

    public function __construct()
    {
        $this->middleware(['auth','verified']);
        // Permitir acceso sin verificar si el módulo no está registrado (como migraciones)
        if (!\App\Models\Module::where('slug', 'seeders')->exists()) {
            $this->middleware('auth')->except(['index', 'execute']);
        }
    }

    public function index()
    {
        $this->authorize('viewAny', Module::class);

        $seeders = $this->discoverSeeders();
        return view('seeders.index', compact('seeders'));
    }

    public function execute(Request $request)
    {
        $this->authorize('viewAny', Module::class);

        $request->validate([
            'seeder' => ['required','string'],
        ]);

        $seederClass = $request->string('seeder');

        // Debug: Log del seeder recibido
        Log::info('Seeder recibido', [
            'seeder_class' => $seederClass,
            'allowed_seeders' => $this->allowedSeeders,
            'user_id' => Auth::id()
        ]);

        // Normalizar comparación: aceptar por FQCN exacto o por nombre base (case-insensitive)
        $allowedByClass = in_array($seederClass, $this->allowedSeeders, true);
        $allowedBaseNames = array_map(function ($fqcn) { return strtolower(class_basename($fqcn)); }, $this->allowedSeeders);
        $allowedByBase = in_array(strtolower(class_basename($seederClass)), $allowedBaseNames, true);

        if (!$allowedByClass && !$allowedByBase) {
            Log::warning('Seeder rechazado', [
                'seeder_class' => $seederClass,
                'allowed_by_class' => $allowedByClass,
                'allowed_by_base' => $allowedByBase,
                'user_id' => Auth::id()
            ]);
            return back()->with('error', 'Seeder no permitido: ' . $seederClass);
        }

        try {
            Artisan::call('db:seed', [
                '--class' => $seederClass,
                '--force' => true,
            ]);

            ModuleLog::createLog(
                Auth::id() ?? 0,
                optional(Module::where('slug','seeders')->first())->id ?? 0,
                'seeder_executed',
                request()->ip(),
                request()->userAgent()
            );

            $output = Artisan::output();
            Log::info('Seeder ejecutado', ['class' => $seederClass, 'output' => $output]);
            return back()->with('success', 'Seeder ejecutado: ' . class_basename($seederClass));
        } catch (\Throwable $e) {
            Log::error('Error al ejecutar seeder', [
                'class' => $seederClass,
                'error' => $e->getMessage(),
            ]);
            return back()->with('error', 'Error al ejecutar seeder: ' . $e->getMessage());
        }
    }

    private function discoverSeeders(): array
    {
        // Basado en la whitelist anterior, devolver pares [class, name]
        return array_map(function ($fqcn) {
            return [
                'class' => $fqcn,
                'name' => class_basename($fqcn),
            ];
        }, $this->allowedSeeders);
    }
}



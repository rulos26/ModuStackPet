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
        'Database\\Seeders\\DatabaseConfigSeeder',
        'Database\\Seeders\\EmailConfigSeeder',
        'Database\\Seeders\\OAuthProviderSeeder',
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

        // En Laravel 11, request->string() retorna Stringable: castear a string
        $seederClass = (string) $request->string('seeder');

        // Debug: Log del seeder recibido
        Log::info('Seeder recibido', [
            'seeder_class' => $seederClass,
            'allowed_seeders' => $this->allowedSeeders,
            'user_id' => Auth::id()
        ]);

        // Normalizar comparación: aceptar por FQCN exacto o por nombre base (case-insensitive)
        $allowedByClass = in_array($seederClass, $this->allowedSeeders, true);

        // Buscar coincidencia case-insensitive en el array completo
        $allowedByCaseInsensitive = false;
        foreach ($this->allowedSeeders as $allowedSeeder) {
            if (strcasecmp($seederClass, $allowedSeeder) === 0) {
                $allowedByCaseInsensitive = true;
                // Si hay diferencia de case, usar el nombre correcto del whitelist
                if ($seederClass !== $allowedSeeder) {
                    $seederClass = $allowedSeeder;
                }
                break;
            }
        }

        // También verificar por nombre base (case-insensitive)
        $allowedBaseNames = array_map(function ($fqcn) {
            return strtolower(class_basename($fqcn));
        }, $this->allowedSeeders);
        $allowedByBase = in_array(strtolower(class_basename($seederClass)), $allowedBaseNames, true);

        // Si coincide por base name pero no por FQCN, encontrar el FQCN correcto
        if ($allowedByBase && !$allowedByClass && !$allowedByCaseInsensitive) {
            foreach ($this->allowedSeeders as $allowedSeeder) {
                if (strcasecmp(class_basename($seederClass), class_basename($allowedSeeder)) === 0) {
                    $seederClass = $allowedSeeder;
                    $allowedByCaseInsensitive = true;
                    break;
                }
            }
        }

        if (!$allowedByClass && !$allowedByCaseInsensitive && !$allowedByBase) {
            Log::warning('Seeder rechazado', [
                'seeder_class' => $seederClass,
                'allowed_by_class' => $allowedByClass,
                'allowed_by_case_insensitive' => $allowedByCaseInsensitive,
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



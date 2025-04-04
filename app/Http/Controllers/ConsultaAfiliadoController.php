<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConsultaAfiliadoController extends Controller
{
    public function index()
    {
        return view('consulta-afiliado');
    }

    public function buscar(Request $request)
    {
        $request->validate([
            'cedula' => 'required|numeric'
        ]);

        $cedula = $request->input('cedula');
        session([
            'cedula_numero' => $request->input('cedula'),
            
        ]);
        $cedula = session('cedula_numero');
      
        // Verificar si existe en datos_basicos
        $existe = DB::table('datos_basicos')->where('cedula_numero', $cedula)->exists();

        if (!$existe) {
            return redirect()->back()->with('error', 'No se encontró afiliado con esta cédula.');
        }

        // Tablas a verificar
        $tablas = [
            'afiliados_coberturas_saluds',
            'afiliados_convivencias',
            'conclusiones',
            'datos_basicos_hijos',
            'direcciones_viviendas',
            'empleos_afiliados',
            'hallazgos_observaciones',
            'hechos_ocurrencias',
            'siniestros',
            'imagen_reclamantes'
        ];

        $routes = [
            'afiliados_coberturas_saluds' => 'afiliados-coberturas-saluds.create',
            'afiliados_convivencias' => 'afiliados-convivencias.create',
            'conclusiones' => 'conclusiones.create',
            'datos_basicos_hijos' => 'datos-basicos-hijos.create',
            'direcciones_viviendas' => 'direcciones-viviendas.create',
            'empleos_afiliados' => 'empleos-afiliados.create',
            'hallazgos_observaciones' => 'hallazgos-observaciones.create',
            'hechos_ocurrencias' => 'hechos-ocurrencias.create',
            'siniestros'=>'siniestros.create',
            //'hechos_ocurrencias'=>'hechos_ocurrencias.create'
        ];
        

        // Verificar cada tabla
        $resultados = [];
        foreach ($tablas as $tabla) {
            $tieneDatos = DB::table($tabla)->where('cedula_numero', $cedula)->exists();
            $resultados[] = [
                'tabla' => $tabla,
                'estado' => $tieneDatos ? 'Lleno' : 'Vacío',
                'ruta' => $routes[$tabla] ?? null // Se asigna solo si existe en la lista de rutas

            ];
        }

        return view('consulta-afiliado', compact('cedula', 'resultados'));
    }
}

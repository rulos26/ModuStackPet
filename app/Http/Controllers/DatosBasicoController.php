<?php

namespace App\Http\Controllers;

use App\Models\DatosBasico;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\DatosBasicoRequest;
use App\Models\Amparo;
use App\Models\EstadosCivile;
use App\Models\TipoDeConvivencia;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class DatosBasicoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $datosBasicos = DatosBasico::paginate();

        return view('datos-basico.index', compact('datosBasicos'))
            ->with('i', ($request->input('page', 1) - 1) * $datosBasicos->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $datosBasico = new DatosBasico();
        $estados_C = EstadosCivile::all();
        $amparo = Amparo::all();
        $tipo_c = TipoDeConvivencia::all();
        return view('datos-basico.create', compact('datosBasico', 'estados_C', 'amparo', 'tipo_c'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        session([
            'cedula_numero' => $request->cedula_numero,
            'nombre_afiliado' => $request->nombre_afiliado
        ]);
        $cedula = session('cedula_numero');
        $nombre = session('nombre_afiliado');

        //dd($cedula,$nombre,$request->cedula_numero,$request->nombre_afiliado, $request->all()); // Esto detendrá la ejecución y mostrará los datos enviados
        try {
            $datos = DatosBasico::create($request->all());

            return Redirect::route('afiliados.create')
                ->with('success', 'DatosBasico creado correctamente.');
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) { // Código de error por clave duplicada
                return Redirect::back()
                    ->withInput()
                    ->withErrors(['cedula_numero' => 'La cédula ya está registrada en el sistema.']);
            }

            return Redirect::back()
                ->withInput()
                ->withErrors(['error' => 'Ocurrió un error inesperado.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $datosBasico = DatosBasico::find($id);

        return view('datos-basico.show', compact('datosBasico'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $datosBasico = DatosBasico::find($id);

        return view('datos-basico.edit', compact('datosBasico'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DatosBasicoRequest $request, DatosBasico $datosBasico): RedirectResponse
    {
        $datosBasico->update($request->validated());

        return Redirect::route('datos-basicos.index')
            ->with('success', 'DatosBasico updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        DatosBasico::find($id)->delete();

        return Redirect::route('datos-basicos.index')
            ->with('success', 'DatosBasico deleted successfully');
    }
}

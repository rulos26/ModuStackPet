<?php

namespace App\Http\Controllers;

use App\Models\DatosBasicosHijo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\DatosBasicosHijoRequest;
use App\Models\TipoDocumento;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class DatosBasicosHijoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $datosBasicosHijos = DatosBasicosHijo::paginate();

        return view('datos-basicos-hijo.index', compact('datosBasicosHijos'))
            ->with('i', ($request->input('page', 1) - 1) * $datosBasicosHijos->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $datosBasicosHijo = new DatosBasicosHijo();
        $tipo_docu=TipoDocumento::all();
        $cedula = session('cedula_numero');
        $nombre = session('nombre_afiliado');
        //dd($tipo_docu);  
        return view('datos-basicos-hijo.create', compact('datosBasicosHijo','tipo_docu','cedula','nombre'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       
        //DatosBasicosHijo::create($request->all());
        // Validación de los datos
        $request->validate([
            'cedula_numero' => 'required|string',
            'numero_hijos' => 'required|integer|min:0',
            'nombre_hijos' => 'required|array|min:1',
            'tipo_documento_hijos' => 'required|array|min:1',
            'documento_hijos' => 'required|array|min:1',
            'edad_hijos' => 'required|array|min:1',
        ]);

        // Recoger los datos enviados
        $cedula = $request->input('cedula_numero');
        $numeroHijos = $request->input('numero_hijos');
        //dd($cedula,  $numeroHijos,$request->all());
        // Guardar los datos de los hijos
        for ($i = 0; $i < $numeroHijos; $i++) {
            DatosBasicosHijo::create([
                'cedula_numero' => $cedula,
                'numero_hijos'=> $numeroHijos,
                'nombre' => $request->input('nombre_hijos')[$i],
                'tipo_documento' => $request->input('tipo_documento_hijos')[$i],
                'documento' => $request->input('documento_hijos')[$i],
                'edad' => $request->input('edad_hijos')[$i],
            ]);
        }

        return Redirect::route('direcciones-viviendas.create')
            ->with('success', 'DatosBasicosHijo created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $datosBasicosHijo = DatosBasicosHijo::find($id);

        return view('datos-basicos-hijo.show', compact('datosBasicosHijo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $datosBasicosHijo = DatosBasicosHijo::find($id);

        return view('datos-basicos-hijo.edit', compact('datosBasicosHijo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DatosBasicosHijoRequest $request, DatosBasicosHijo $datosBasicosHijo): RedirectResponse
    {
        $datosBasicosHijo->update($request->validated());

        return Redirect::route('datos-basicos-hijos.index')
            ->with('success', 'DatosBasicosHijo updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        DatosBasicosHijo::find($id)->delete();

        return Redirect::route('datos-basicos-hijos.index')
            ->with('success', 'DatosBasicosHijo deleted successfully');
    }
}

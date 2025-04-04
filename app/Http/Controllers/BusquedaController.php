<?php

namespace App\Http\Controllers;

use App\Models\Busqueda;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\BusquedaRequest;
use App\Models\Aspirante;
use App\Models\Informe;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class BusquedaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $busquedas = Busqueda::paginate();

        return view('busqueda.index', compact('busquedas'))
            ->with('i', ($request->input('page', 1) - 1) * $busquedas->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $busqueda = new Busqueda();

        return view('busqueda.create', compact('busqueda'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BusquedaRequest $request)
    {
        $cedula = $request->cedula;
        session(['cedula' => $cedula]);
        $id = auth()->id();
        $buqqueda = Busqueda::where('cedula', $cedula)->first();
        //$informe = Informe::where('cedula', $cedula)->first();
        //var_dump($buqqueda->empty());
        //dd($id, $request, $cedula, $buqqueda, $informe);
        if (!$buqqueda) {
            Busqueda::create([
                'cedula' => $cedula,

                // Otras columnas según tu estructura de la tabla Busqueda
            ]);
          /*   Informe::create([
                'id_usuario' => $id,
                'cedula' => $cedula,
                'terminado' => '0',
                // Otras columnas según tu estructura de la tabla Informe
            ]);
            return view('validar-aspirante.aspirante_not'); //aspirante no existe
        } else {
            $terminado = $informe->first()->terminado; */

            /* if ($terminado == 0) {
                $creado = 0;
                // Obtén los aspirantes por cédula
                $aspirantes = Aspirante::where('cedula', $cedula)->with(['ciudad', 'lugar_expedicion', 'municipio'])->get();

                if ($aspirantes->isEmpty()) {
                    $creado = 0; // esta vacia  se pèude crear  el resgistro
                } else {
                    $creado = 1; // esta esta lleno no se pèude crear  el resgistro
                }
                return view('aspirante.index', compact('creado','aspirantes')); //aspirante existe
                //return view('aspirante.index', compact('aspirantes', 'creado'));
            } elseif ($terminado == 1) {
                return view('validar-aspirante.aspirante_term'); //ifnorme termiando
            } */
        }

        /* dd($id, $request, $cedula, $buqqueda, $informe);



        //Busqueda::create($request->validated());

        return Redirect::route('busquedas.index')
            ->with('success', 'Busqueda created successfully.'); */
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $busqueda = Busqueda::find($id);

        return view('busqueda.show', compact('busqueda'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $busqueda = Busqueda::find($id);

        return view('busqueda.edit', compact('busqueda'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BusquedaRequest $request, Busqueda $busqueda): RedirectResponse
    {
        $busqueda->update($request->validated());

        return Redirect::route('busquedas.index')
            ->with('success', 'Busqueda updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Busqueda::find($id)->delete();

        return Redirect::route('busquedas.index')
            ->with('success', 'Busqueda deleted successfully');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Municipio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\MunicipioRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class MunicipioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $municipios = Municipio::paginate();

        return view('municipio.index', compact('municipios'))
            ->with('i', ($request->input('page', 1) - 1) * $municipios->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $municipio = new Municipio();

        return view('municipio.create', compact('municipio'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MunicipioRequest $request): RedirectResponse
    {
        Municipio::create($request->validated());

        return Redirect::route('municipios.index')
            ->with('success', 'Municipio created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        /*  $municipio = Municipio::find($id);

        return view('municipio.show', compact('municipio')); */

        // Obtener el parámetro del query string
        $departamento_id = $request->query('departamento_id');
        //dd($request, $departamento_id, $id);
        $municipios = Municipio::where('id_departamento', $departamento_id)->get();
        //dd($municipios,$request, $departamento_id, $id);
        if ($municipios->isEmpty()) {
            return response()->json(['message' => 'No se encontraron municipios'], 404);
        }

        return response()->json($municipios);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $municipio = Municipio::find($id);

        return view('municipio.edit', compact('municipio'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MunicipioRequest $request, Municipio $municipio): RedirectResponse
    {
        $municipio->update($request->validated());

        return Redirect::route('municipios.index')
            ->with('success', 'Municipio updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Municipio::find($id)->delete();

        return Redirect::route('municipios.index')
            ->with('success', 'Municipio deleted successfully');
    }

    public function porDepartamento(Request $request)
    {
        dd($request);

        $departamento_id = $request->departamento_id;

        $municipios = Municipio::where('id_departamento', $departamento_id)->get();

        if ($municipios->isEmpty()) {
            return response()->json(['message' => 'No se encontraron municipios'], 404);
        }

        return response()->json($municipios);
    }
}

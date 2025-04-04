<?php

namespace App\Http\Controllers;

use App\Models\FiltrosPdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\FiltrosPdfRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class FiltrosPdfController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $filtrosPdfs = FiltrosPdf::paginate();

        return view('filtros-pdf.index', compact('filtrosPdfs'))
            ->with('i', ($request->input('page', 1) - 1) * $filtrosPdfs->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $filtrosPdf = new FiltrosPdf();

        return view('filtros-pdf.create', compact('filtrosPdf'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FiltrosPdfRequest $request): RedirectResponse
    {
        FiltrosPdf::create($request->validated());

        return Redirect::route('filtros-pdfs.index')
            ->with('success', 'FiltrosPdf created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        //dd($id);
        $filtrosPdf = FiltrosPdf::all();
        if($id == 1){
            foreach ($filtrosPdf as $filtrosPdf) {
                $id_filtro = $filtrosPdf->id;
                $visibleNew = 1;
                $filtrosPdf = FiltrosPdf::find($id_filtro);
                $filtrosPdf->visible = $visibleNew;
                $filtrosPdf->save();
            }
    
        }else{
            foreach ($filtrosPdf as $filtrosPdf) {
                $id_filtro = $filtrosPdf->id;
                $visibleNew = 0;
                $filtrosPdf = FiltrosPdf::find($id_filtro);
                $filtrosPdf->visible = $visibleNew;
                $filtrosPdf->save();
            }
    
        }
        
        $filtrosPdfs = FiltrosPdf::all();
        $i = 1;
        return view('filtros-pdf.index', compact('filtrosPdfs', 'i'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $filtrosPdf = FiltrosPdf::find($id);
        $visible = $filtrosPdf->visible;
        if ($visible == 1) {
            $visibleNew = 0;
        } else {
            $visibleNew = 1;
        }
        $filtrosPdf = FiltrosPdf::find($id);
        $filtrosPdf->visible = $visibleNew;
        $filtrosPdf->save();
        $filtrosPdfs = FiltrosPdf::all();
        $i = 1;
        return view('filtros-pdf.index', compact('filtrosPdfs', 'i'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FiltrosPdfRequest $request, FiltrosPdf $filtrosPdf): RedirectResponse
    {
        $filtrosPdf->update($request->validated());

        return Redirect::route('filtros-pdfs.index')
            ->with('success', 'FiltrosPdf updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        FiltrosPdf::find($id)->delete();

        return Redirect::route('filtros-pdfs.index')
            ->with('success', 'FiltrosPdf deleted successfully');
    }

    public function toggleVisible(Request $request)
    {

        $filtrosPdf = FiltrosPdf::find($request->id);
        $filtrosPdf->visible = $request->visible;
        $filtrosPdf->save();

        return response()->json(['message' => 'Visibility updated successfully.']);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImagenReclamante;
use Illuminate\Support\Facades\Storage;

class ImagenReclamanteController extends Controller
{
    public function index()
    {
        $imagenes = ImagenReclamante::all();
        return view('imagenes.index', compact('imagenes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cedula_numero' => 'required|exists:afiliados,cedula_numero',
            'tipo_imagen' => 'required|string',
            'imagen' => 'required|image|max:2048',
        ]);
        
        $path = $request->file('imagen')->store("imagenes/{$request->cedula_numero}/{$request->tipo_imagen}", 'public');
        
        ImagenReclamante::create([
            'cedula_numero' => $request->cedula_numero,
            'tipo_imagen' => $request->tipo_imagen,
            'ruta_imagen' => $path,
        ]);
        
        return back()->with('success', 'Imagen subida correctamente.');
    }
}
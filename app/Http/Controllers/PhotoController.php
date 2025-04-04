<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\PhotoRequest;
use App\Models\Busqueda;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $cedula = session('cedula');
        // Verificar si la variable está vacía
        if (empty($cedula)) {
            $busqueda = new Busqueda();

            return view('busqueda.create', compact('busqueda'));
        }
        $photos = Photo::where('cedula', $cedula)->get();
        if ($photos->isEmpty()) {
            $creado = 0; // esta vacia  se pèude crear  el resgistro
        } else {
            $creado = 1; // esta esta lleno no se pèude crear  el resgistro
        }
        return view('photo.index', compact('photos','creado'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $cedula = session('cedula');
        // Verificar si la variable está vacía
        if (empty($cedula)) {
            $busqueda = new Busqueda();

            return view('busqueda.create', compact('busqueda'));
        }
        $photo = new Photo();

        return view('photo.create', compact('photo','cedula'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
       
        $cedula = session('cedula');
        $imageName = $cedula . '_' . time() . '.' . $request->photo->extension();  
        $destinationPath = public_path('images/' . $cedula);
        
        // Crear el directorio si no existe
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }
        $request->photo->move($destinationPath, $imageName);
        Photo::create([
            'cedula' => $request->cedula,
            'path' => $imageName
        ]);
        return Redirect::route('photos.index')
            ->with('success', 'Photo created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $photo = Photo::find($id);

        return view('photo.show', compact('photo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $cedula = session('cedula');
        if (empty($cedula)) {
            $busqueda = new Busqueda();

            return view('busqueda.create', compact('busqueda'));
        }
        $photo = Photo::find($id);

        return view('photo.edit', compact('photo','cedula'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Photo $photo): RedirectResponse
    {
        $cedula = session('cedula');
        $imageName = $cedula . '_' . time() . '.' . $request->photo->extension();
        $existingImagePath = 'images/' . $cedula . '/' . $photo->path;
    
        $existingDirectoryPath = 'images/' . $cedula;

        if (file_exists($existingImagePath)) {
            echo "El archivo existe.";
            if (is_file($existingImagePath)) {
                if (unlink($existingImagePath)) {
                    echo "El archivo $photo->path fue eliminado con éxito.\n";
                } else {
                    echo "No se pudo eliminar el archivo $photo->path.\n";
                }
            }
        } else {
            echo "El archivo no existe.";
        }
        //dd($existingImagePath,$existingDirectoryPath);
        $destinationPath = public_path('images/' . $cedula);
        $request->photo->move($destinationPath, $imageName);
        // Actualizar los datos de la foto en la base de datos
        $photo->update([
            'cedula' => $cedula,
            'path' => $imageName
        ]);

        return Redirect::route('photos.index')
            ->with('success', 'Photo updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Photo::find($id)->delete();

        return Redirect::route('photos.index')
            ->with('success', 'Photo deleted successfully');
    }
}

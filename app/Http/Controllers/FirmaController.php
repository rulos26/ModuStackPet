<?php

namespace App\Http\Controllers;

use App\Models\Firma;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\FirmaRequest;
use App\Models\Busqueda;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class FirmaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $firmas = Firma::paginate();

        return view('firma.index', compact('firmas'))
            ->with('i', ($request->input('page', 1) - 1) * $firmas->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $cedula = session('cedula');
        // Verificar si la variable de sesión 'cedula' existe y tiene datos
        if (empty($cedula)) {
            // Redirigir a la ruta 'verificaciones.create' si no hay datos en 'cedula'
            return redirect()->route('verificaciones.create');
        }
        $firma = new Firma();

        return view('firma.create', compact('firma', 'cedula'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Decodificar la imagen base64
        $base64Image = $request->input('signature');
        // Remover la parte que no es base64 (data:image/png;base64,)
        $base64Image = str_replace('data:image/png;base64,', '', $base64Image);
        $base64Image = str_replace(' ', '+', $base64Image);
        $imageData = base64_decode($base64Image);
        // Obtener el número de cédula de la sesión en Laravel (esto es un ejemplo, adapta según tu implementación real)
        $cedula = session('cedula');
        // Verificar si la variable de sesión 'cedula' existe y tiene datos
        if (empty($cedula)) {
            $busqueda = new Busqueda();

            return view('busqueda.create', compact('busqueda')); 
        }
        // Crear la estructura de carpetas si no existe
        $folderPath = 'public/images/' . $cedula . '/firma';
        Storage::makeDirectory($folderPath);
        // Nombre único para la imagen
        $fileName = time() . '-' . $cedula . '.png';

        // Almacenar la imagen en la carpeta respectiva
        Storage::put($folderPath . '/' . $fileName, $imageData);

        return Redirect::route('firmas.select_firma')
            ->with('success', 'Firma created successfully.');
    }

    public function select_firma(): View
    {
        dd('hola');
        $cedula = session('cedula');


        ///return view('firma.select_firma', compact('cedula'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        //dd('hola');
        
        $firma = Firma::find($id);
        $cedula = session('cedula');
        if (empty($cedula)) {
            $busqueda = new Busqueda();

            return view('busqueda.create', compact('busqueda'));
        }
        // Define el nombre y la ruta de la carpeta
        $directory = 'public/images/' . $cedula . '/firma';

        // Obtén todos los archivos en la carpeta
        $files = Storage::files($directory);

        // Genera URLs de acceso público para los archivos
        $validImages = [];
        $images = [];
        foreach ($files as $file) {
            if ($this->isValidImage(Storage::path($file))) {
                $images[] = Storage::url($file);
                $validImages[] = [
                    'name' => pathinfo($file, PATHINFO_FILENAME),
                    'path' => Storage::url($file)
                ];
            } else {

                // Eliminar imagen no válida

                Storage::delete($file);
            }
        }
        // Guardar información en variables
        $imageNames = array_column($validImages, 'name');
        $imagePaths = array_column($validImages, 'path');
        //dd($imagePaths,$imageNames);
        /* Firma::create([
            'cedula' => $cedula,
            'nombre' => $imagePaths[0],
        ]); */
        $imagePaths_2='storage/images/'.$cedula.'/firma/'.$imageNames[0].'.png';
        session(['imagePaths' => $imagePaths_2]);
        //dd($imagePaths,$imagePaths_2);
        return view('firma.show', ['images' => $images], compact('imageNames', 'imagePaths'));
        /* return view('firma.show', compact('firma')); */
    }
    // Método para validar si un archivo es una imagen
    private function isValidImage($path)
    {
        $f = finfo_open(FILEINFO_MIME_TYPE);
        $result = finfo_file($f, $path);
        finfo_close($f);
        return in_array($result, ['image/png', 'image/jpeg', 'image/gif']);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {

        $cedula = session('cedula');

        // Verificar si la variable de sesión 'cedula' existe y tiene datos
        if (empty($cedula)) {
            // Redirigir a la ruta 'verificaciones.create' si no hay datos en 'cedula'
            return redirect()->route('verificaciones.create');
        }
        // Define el nombre y la ruta de la carpeta
        $directory = 'public/' . $cedula . '/firma';

        // Obtén todos los archivos en la carpeta
        $files = Storage::files($directory);
        foreach ($files as $file) {
            Storage::delete($file);
        }

        $firma = Firma::where('cedula', $cedula)->first();
        // dd($firma);
        if ($firma) {
            $firma->delete();
        }
        return Redirect::route('firmas.create')
            ->with('success', 'Firma created successfully.');
        //$firma = Firma::find($id);

        //return view('firma.edit', compact('firma'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FirmaRequest $request, Firma $firma): RedirectResponse
    {
        $firma->update($request->validated());

        return Redirect::route('firmas.index')
            ->with('success', 'Firma updated successfully');
    }

    public function destroy(): RedirectResponse
    {
        dd('hola');
        /* Firma::find($id)->delete();

        return Redirect::route('firmas.index')
            ->with('success', 'Firma deleted successfully'); */
    }
}

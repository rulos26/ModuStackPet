<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
//use PDF;

class PDFController extends Controller
{
    public function generarPDF()
    {
        $data = ['title' => 'Bienvenido a PDF'];
        $pdf = Pdf::loadView('pdf.ejemplo', $data);
        return $pdf->stream('archivo-ejemplo.pdf'); // O ->stream() para mostrarlo en el navegador
    }

    public function generarPDFMascota()
    {
        $mascota = \App\Models\Mascota::with(['raza', 'barrio', 'user'])->find(5);

        if (!$mascota) {
            return redirect()->back()->with('error', 'No se encontró la mascota especificada.');
        }

        // Ruta de la imagen por defecto
        $imagenPorDefecto = public_path('avatars/1110456003/mascotas/thanos.png');

        // Ruta de la imagen de la mascota
        $rutaImagen = $mascota->avatar
            ? public_path($mascota->avatar)
            : $imagenPorDefecto;

        // Verificar si la imagen existe
        $rutaImagen = file_exists($rutaImagen) ? $rutaImagen : $imagenPorDefecto;

        $pdf = Pdf::loadView('pdf.mascotas', compact('mascota', 'rutaImagen'));
        return $pdf->stream('mascota-informacion.pdf');
    }
}

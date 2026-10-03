<?php

namespace App\Http\Controllers;

use App\Models\Mascota;
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

    /**
     * SEG-042: exige sesión verificada y MascotaPolicy::view (dueño, Admin o
     * Superadmin). Antes generaba el PDF de la mascota id=5 sin autenticación,
     * con email y teléfono del propietario.
     */
    public function generarPDFMascota(?Mascota $mascota = null)
    {
        if (!$mascota) {
            return redirect()->route('mascotas.index')
                ->with('error', 'Seleccione la mascota de la que desea el PDF.');
        }

        $this->authorize('view', $mascota);
        $mascota->load(['raza', 'user']); // Mascota no tiene relación barrio (antes rompía con 500)

        // Ruta de la imagen por defecto
        $imagenPorDefecto = public_path('ruta_default_avatar.png');

        // Ruta de la imagen de la mascota
        $rutaImagen = $mascota->avatar
            ? public_path($mascota->avatar)
            : $imagenPorDefecto;

        // Verificar si la imagen existe
        $rutaImagen = file_exists($rutaImagen) ? $rutaImagen : $imagenPorDefecto;

        $pdf = Pdf::loadView('pdf.mascotas', compact('mascota', 'rutaImagen'));
        return $pdf->stream('informacion-mascota-' . strtolower(str_replace(' ', '-', $mascota->nombre)) . '.pdf');
    }
}

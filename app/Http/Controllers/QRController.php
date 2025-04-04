<?php

namespace App\Http\Controllers;

use App\Models\DatosBasico;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
class QRController extends Controller
{
    public function generarQR($cedula)
    {
        $qr = QrCode::size(200)->generate($cedula);
        return view('qr.mostrar', compact('qr'));
    }

    public function validateQR(Request $request)
    {
        $cedula = $request->cedula;
        $usuario = DatosBasico::where('cedula', $cedula)->first();

        if ($usuario) {
            // Generar PDF
            $pdf = Pdf::loadView('pdf.datos_usuario', compact('usuario'));
            return $pdf->download("datos_{$cedula}.pdf");
        } else {
            return redirect()->route('qr.scan')->with('error', 'Cédula no encontrada.');
        }
    }
}

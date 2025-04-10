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
        return $pdf->download('archivo-ejemplo.pdf'); // O ->stream() para mostrarlo en el navegador
    }
}

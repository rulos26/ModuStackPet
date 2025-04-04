<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function generatePdf()
    {
        // Datos que se pasarán a la vista
        $data = [
            'clientName' => 'Juan Pérez',
            'date' => now()->format('d/m/Y'),
            'items' => [
                ['product' => 'Producto A', 'quantity' => 2, 'price' => 150],
                ['product' => 'Producto B', 'quantity' => 1, 'price' => 300],
            ],
            'total' => 600
        ];

        // Cargar la vista y pasarle los datos
        $pdf = Pdf::loadView('invoice', $data);

        // Descargar el PDF
        return $pdf->download('factura.pdf');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\VerificacionAutorizacioneRequest;
use App\Models\Afiliado;
use App\Models\AfiliadosConvivencia;
use App\Models\Aspirante;
use App\Models\Autorizacion;
use App\Models\Autorizacione;
use App\Models\CamaraComercio;
use App\Models\CartaAutorizacione;
use App\Models\CartasImagene;
use App\Models\ComposicionFamiliare;
use App\Models\DatosBasico;
use App\Models\Departamento;
use App\Models\Evaluado;
use App\Models\FiltrosPdf;
use App\Models\Firma;
use App\Models\Foto;
use App\Models\InformacionPareja;
use App\Models\InventarioEnsere;
use App\Models\Municipio;
use App\Models\Patrimonio;
use App\Models\Salud;
use App\Models\Sectore;
use App\Models\ServiciosPublico;
use App\Models\Siniestro;
use App\Models\TipoDocumento;
use App\Models\Ubicacione;
use App\Models\User;
use App\Models\VerificacionAutorizacione;
use App\Models\Vivienda;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManagerStatic as Image;
use Imagick;


class ReportePDFController extends Controller
{
    public function CartaReporte()
    {

        //$cedula = session('cedula_numero');
        $cedula = session('cedula_numero');
        /* $cedula = session('cedula');
        // Verificar si la variable de sesión 'cedula' existe y tiene datos
        if (empty($cedula)) {
            // Redirigir a la ruta 'verificaciones.create' si no hay datos en 'cedula'
            return redirect()->route('verificaciones.create');
        } */

        //dd($vivienda) ;
        //dd( $filtro_transito,$filtro_pdf, $filtro_pdfArray);

        $afiliado = Autorizacion::where('cedula', $cedula)->first();

        if ($afiliado) {
            $afiliadoArray = $afiliado->toArray();
        } else {
            $afiliadoArray = [];
        }
        //dd( $cedula,$afiliado,$afiliadoArray);  
        $hola = '';
        $data = compact(
            'hola',
            'afiliadoArray'


        );
        // Generar el PDF
        $pdf = PDF::loadView('reportes.carta.temple', $data);

        // Retornar el PDF para su visualización en el navegador
        return $pdf->stream('invoice.pdf');
    }

    public function  EvaluadoReporte()
    {
        //$cedula = session('cedula_numero');
        if (session()->has('cedula_numero')) {
            $cedula = session('cedula_numero');
            // La variable existe y puedes continuar con la validación
        } else {
            // La variable no existe en la sesión
            // Maneja este caso según sea necesario
            return redirect()->route('consulta.afiliado.buscar');
        }

        $registros = DatosBasico::where('cedula_numero', $cedula)->first();
        $afiliado = Afiliado::where('cedula_numero', $cedula)
            ->with(['datosBasico', 'departamento', 'municipio'])
            ->first();
        $siniestro = Siniestro::where('cedula_numero', $cedula)->first();
        $convivencia=AfiliadosConvivencia::with(['afiliado', 'estadosCivile'])
        ->where('cedula_numero', $cedula)
        ->first();


        if ($registros) {
            $registrosArray = $registros->toArray();
        } else {
            $registrosArray = [];
        }
        if ($afiliado) {
            $afiliadoArray = $afiliado->toArray();
        } else {
            $afiliadoArray = [];
        }
        if ($siniestro) {
            $siniestroArray = $siniestro->toArray();
        } else {
            $siniestroArray = [];
        }
        if ($convivencia) {
            $convivenciaArray = $convivencia->toArray();
            } else {
                $convivenciaArray = [];
                }

        $id_municipio = $afiliado->municipio;
        $id_depa = $afiliado->departamento;
        $municipio = Municipio::where('id', $id_municipio)->first();
        $departamento = Departamento::where('id', $id_depa)->first();
        $id_municipio_siniestro = $siniestro->municipio;
        $id_depa_siniestro = $siniestro->departamento;
        $municipio_siniestro = Municipio::where('id', $id_municipio_siniestro)->first();
        $departamento_siniestro = Departamento::where('id', $id_depa_siniestro)->first();
        //$id_estado_civil=$registros->

        if ($municipio) {
            $municipioArray = $municipio->toArray();
        } else {
            $municipioArray = [];
        }
        if ($departamento) {
            $departamentoArray = $departamento->toArray();
        } else {
            $departamentoArray = [];
        }
        if ($municipio_siniestro) {
            $municipioArray_siniestro = $municipio_siniestro->toArray();
        } else {
            $municipioArray_siniestro = [];
        }
        if ($departamento_siniestro) {
            $departamentoArray_siniestro = $departamento_siniestro->toArray();
        } else {
            $departamentoArray_siniestro = [];
        }




        /* $cedula = session('cedula');
        // Verificar si la variable de sesión 'cedula' existe y tiene datos
        if (empty($cedula)) {
            // Redirigir a la ruta 'verificaciones.create' si no hay datos en 'cedula'
            return redirect()->route('verificaciones.create');
        } */

        //dd($vivienda) ;
        //dd( $filtro_transito,$filtro_pdf, $filtro_pdfArray);
        $hola = '';
        $data = compact(
            'hola',
            'registrosArray',
            'afiliadoArray',
            'municipioArray',
            'departamentoArray',
            'municipioArray_siniestro',
            'departamentoArray_siniestro',
            'siniestroArray',
            'convivenciaArray'


        );

        //dd($convivencia);
        // Generar el PDF
        $pdf = PDF::loadView('reportes.evaluado.temple', $data);

        // Retornar el PDF para su visualización en el navegador
        return $pdf->stream('invoice.pdf');
    }
}

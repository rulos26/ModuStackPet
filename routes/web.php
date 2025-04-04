<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AfiliadoController,
    AfiliadosCoberturasSaludController,
    AfiliadosConvivenciaController,
    AmparoController,
    AutorizacionController,
    BusquedaController,
    CartaAutorizacioneController,
    CartasImageneController,
    CoberturasSaludController,
    ConclusioneController,
    DatosBasicoController,
    DatosBasicosHijoController,
    DepartamentoController,
    DireccionesViviendaController,
    EmpleosAfiliadoController,
    EstadosCivileController,
    FiltrosPdfController,
    FirmaController,
    HallazgosObservacioneController,
    HechosOcurrenciaController,
    InvoiceController,
    LinkController,
    MotivosMuerteController,
    MuertesOrigenController,
    MunicipioController,
    PaiseController,
    PersonasAfiliadaController,
    PhotoController,
    ReclamanteController,
    ReclamantesAfiliadoController,
    RegimenesSaludController,
    ReportePDFController,
    SiniestroController,
    TipoDeConvivenciaController,
    TiposAfiliacioneController,
    TiposDePropiedadeController,
    TiposDeViviendaController,
    UserController,
    VerificacioneController
};
use App\Http\Controllers\TipoDocumentoController;
use App\Http\Controllers\ImagenReclamanteController;
use App\Models\CartaAutorizacione;
use App\Http\Controllers\ConsultaAfiliadoController;

use App\Http\Controllers\QRController;
use App\Http\Controllers\PDFController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    
    /* Rutas de Reportes */
    Route::prefix('reporte')->group(function () {
        Route::get('/carta', [ReportePDFController::class, 'CartaReporte'])->name('reporte.carta');
        Route::get('/evaluado', [ReportePDFController::class, 'EvaluadoReporte'])->name('reporte.evaluado');
    });
    
    /* Rutas de Tablas Relacionadas */
    Route::resources([
        'links' => LinkController::class,
        'busquedas' => BusquedaController::class
    ]);
    
    Route::resources([
        'photos' => PhotoController::class,
        'filtros-pdfs' => FiltrosPdfController::class,
        'users' => UserController::class,
        'firmas' => FirmaController::class,
        'carta-autorizaciones' => CartaAutorizacioneController::class,
        'cartas-imagenes' => CartasImageneController::class,
        'personas-afiliadas' => PersonasAfiliadaController::class,
        'municipios' => MunicipioController::class,
        'estados-civiles' => EstadosCivileController::class,
        'tipos-afiliaciones' => TiposAfiliacioneController::class,
        'coberturas-saluds' => CoberturasSaludController::class,
        'hechos-ocurrencias' => HechosOcurrenciaController::class,
        'conclusiones' => ConclusioneController::class,
        'datos-basicos' =>  DatosBasicoController::class,
        'amparos' =>  AmparoController::class,
        'tipo-de-convivencias' =>  TipoDeConvivenciaController::class,
        'afiliados'=> AfiliadoController::class,
        'departamentos'=> DepartamentoController::class,
        'paises'=> PaiseController::class
    ]);
    Route::resource('datos-basicos', DatosBasicoController::class);
    Route::resource('afiliados-convivencias', AfiliadosConvivenciaController::class);
    Route::resource('tipo-documentos', TipoDocumentoController::class);
    Route::resource('datos-basicos-hijos', DatosBasicosHijoController::class);
    Route::resource('direcciones-viviendas', DireccionesViviendaController::class);
    Route::resource('empleos-afiliados', EmpleosAfiliadoController::class);
    Route::resource('afiliados-coberturas-saluds', AfiliadosCoberturasSaludController::class);
    Route::resource('hallazgos-observaciones', HallazgosObservacioneController::class);
    Route::resource('siniestros', SiniestroController::class);
    Route::resource('motivos-muertes', MotivosMuerteController::class);
    Route::resource('hechos-ocurrencias', HechosOcurrenciaController::class);
    Route::resource('muertes-origens', MuertesOrigenController::class);
    Route::resource('reclamantes', ReclamanteController::class);
    Route::resource('conclusiones', ConclusioneController::class);
    Route::resource('reclamantes-afiliados', ReclamantesAfiliadoController::class);
    Route::resource('verificaciones', VerificacioneController::class);

   

Route::get('/imagenes', [ImagenReclamanteController::class, 'index'])->name('imagenes.index');
Route::post('/imagenes', [ImagenReclamanteController::class, 'store'])->name('imagenes.store');
Route::get('/autorizacion', [AutorizacionController::class, 'create'])->name('autorizacion.create');
Route::post('/autorizacion', [AutorizacionController::class, 'store'])->name('autorizacion.store');
// web.php
Route::get('/municipios/porDepartamento', [MunicipioController::class, 'porDepartamento'])->name('municipios.porDepartamento');
Route::resource('tipos-de-viviendas', TiposDeViviendaController::class);
Route::resource('tipos-de-propiedades', TiposDePropiedadeController::class);


Route::get('/consulta-afiliado', [ConsultaAfiliadoController::class, 'index'])->name('consulta.afiliado');
Route::post('/consulta-afiliado', [ConsultaAfiliadoController::class, 'buscar'])->name('consulta.afiliado.buscar');
Route::resource('regimenes-saluds', RegimenesSaludController::class);

    /* Rutas Adicionales */
    Route::post('filtros-pdfs/toggleVisible', [FiltrosPdfController::class, 'toggleVisible'])->name('filtros-pdfs.toggleVisible');
    Route::post('/save-signature', [FirmaController::class, 'store'])->name('firmas.store');
    Route::get('firmas/select_firma', [FirmaController::class, 'select_firma'])->name('firmas.select_firma');
    Route::get('/generate-pdf', [InvoiceController::class, 'generatePdf'])->name('generate.pdf');
});

Route::get('/ruta-deseada', [CartaAutorizacioneController::class, 'cartaNew'])->name('carta-autorizaciones.cartaNew');

Route::get('/qr/{cedula}', [QRController::class, 'generarQR'])->name('qr.generar');

// Ruta para generar el QR
Route::get('/generar-qr/{cedula}', [QRController::class, 'generarQR']);

// Ruta para escanear el QR y redirigir al PDF
Route::get('/escanear-qr', [QRController::class, 'escanearQR']);

// Ruta para generar el PDF con los datos básicos
//Route::get('/generar-pdf/{cedula}', [PDFController::class, 'generarPDF']);
Route::get('/qr-scan', function () {
    return view('qr_scan');
})->name('qr.scan');

Route::post('/qr-validate', [QRController::class, 'validateQR'])->name('qr.validate');

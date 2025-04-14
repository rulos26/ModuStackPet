<?php

namespace App\Http\Controllers;

use App\Models\VacunasCertificacione;
use App\Models\Mascota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class VacunasCertificacionesController extends Controller
{
    /**
     * Constructor del controlador.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Genera la ruta para almacenar los documentos de la mascota.
     *
     * @param string $cedula
     * @param string $nombreMascota
     * @param string $tipoDocumento
     * @return string
     */
    private function generarRutaDocumento($cedula, $nombreMascota, $tipoDocumento)
    {
        $nombreArchivo = Str::slug($nombreMascota . '_' . $tipoDocumento . '_' . time());
        return "documentos_mascotas/{$cedula}/{$nombreArchivo}";
    }

    /**
     * Maneja el almacenamiento de un archivo.
     *
     * @param \Illuminate\Http\UploadedFile $archivo
     * @param string $ruta
     * @return string
     */
    private function almacenarArchivo($archivo, $ruta)
    {
        $extension = $archivo->getClientOriginalExtension();
        $nombreArchivo = $ruta . '.' . $extension;

        // Asegurar que el directorio existe
        $directorioBase = dirname($nombreArchivo);
        if (!Storage::disk('public')->exists($directorioBase)) {
            Storage::disk('public')->makeDirectory($directorioBase, 0755, true);
        }

        Storage::disk('public')->put($nombreArchivo, file_get_contents($archivo));
        return $nombreArchivo;
    }

    /**
     * Muestra una lista de los registros de vacunas y certificaciones.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vacunasCertificaciones = VacunasCertificacione::with('mascota')->paginate(10);
        $i = ($vacunasCertificaciones->currentPage() - 1) * $vacunasCertificaciones->perPage();
        return view('vacunas_certificaciones.index', compact('vacunasCertificaciones', 'i'));
    }

    /**
     * Muestra el formulario para crear un nuevo registro.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $mascotas = Mascota::all();
        return view('vacunas_certificaciones.create', compact('mascotas'));
    }

    /**
     * Almacena un nuevo registro de vacunas y certificaciones.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_mascota' => 'required|exists:mascotas,id',
            'fecha_ultima_vacuna' => 'required|date|before_or_equal:today',
            'operaciones' => 'nullable|string',
            'certificado_veterinario' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'cedula_propietario' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        $mascota = Mascota::findOrFail($request->id_mascota);
        $data = $request->all();

        // Manejar el archivo del certificado veterinario
        if ($request->hasFile('certificado_veterinario')) {
            $ruta = $this->generarRutaDocumento(
                Auth::id(),
                $mascota->nombre,
                'vacunas'
            );
            $data['certificado_veterinario'] = $this->almacenarArchivo(
                $request->file('certificado_veterinario'),
                $ruta
            );
        }

        // Manejar el archivo de la cédula del propietario
        if ($request->hasFile('cedula_propietario')) {
            $ruta = $this->generarRutaDocumento(
                Auth::id(),
                $mascota->nombre,
                Auth::id()
            );
            $data['cedula_propietario'] = $this->almacenarArchivo(
                $request->file('cedula_propietario'),
                $ruta
            );
        }

        VacunasCertificacione::create($data);

        return redirect()->route('vacunas_certificaciones.index')
            ->with('success', 'Registro de vacunas y certificaciones creado exitosamente.');
    }

    /**
     * Muestra los detalles de un registro específico.
     *
     * @param  \App\Models\VacunasCertificacione  $vacunasCertificacione
     * @return \Illuminate\Http\Response
     */
    public function show(VacunasCertificacione $vacunasCertificacione)
    {
        return view('vacunas_certificaciones.show', compact('vacunasCertificacione'));
    }

    /**
     * Muestra el formulario para editar un registro.
     *
     * @param  \App\Models\VacunasCertificacione  $vacunasCertificacione
     * @return \Illuminate\Http\Response
     */
    public function edit(VacunasCertificacione $vacunasCertificacione)
    {
        $mascotas = Mascota::all();
        return view('vacunas_certificaciones.edit', compact('vacunasCertificacione', 'mascotas'));
    }

    /**
     * Actualiza un registro específico.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\VacunasCertificacione  $vacunasCertificacione
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VacunasCertificacione $vacunasCertificacione)
    {
        $request->validate([
            'id_mascota' => 'required|exists:mascotas,id',
            'fecha_ultima_vacuna' => 'required|date|before_or_equal:today',
            'operaciones' => 'nullable|string',
            'certificado_veterinario' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'cedula_propietario' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        $mascota = Mascota::findOrFail($request->id_mascota);
        $data = $request->all();

        // Manejar el archivo del certificado veterinario
        if ($request->hasFile('certificado_veterinario')) {
            // Eliminar el archivo anterior si existe
            if ($vacunasCertificacione->certificado_veterinario) {
                Storage::disk('public')->delete($vacunasCertificacione->certificado_veterinario);
            }
            $ruta = $this->generarRutaDocumento(
                Auth::id(),
                $mascota->nombre,
                'vacunas'
            );
            $data['certificado_veterinario'] = $this->almacenarArchivo(
                $request->file('certificado_veterinario'),
                $ruta
            );
        }

        // Manejar el archivo de la cédula del propietario
        if ($request->hasFile('cedula_propietario')) {
            // Eliminar el archivo anterior si existe
            if ($vacunasCertificacione->cedula_propietario) {
                Storage::disk('public')->delete($vacunasCertificacione->cedula_propietario);
            }
            $ruta = $this->generarRutaDocumento(
                Auth::id(),
                $mascota->nombre,
                Auth::id()
            );
            $data['cedula_propietario'] = $this->almacenarArchivo(
                $request->file('cedula_propietario'),
                $ruta
            );
        }

        $vacunasCertificacione->update($data);

        return redirect()->route('vacunas_certificaciones.index')
            ->with('success', 'Registro de vacunas y certificaciones actualizado exitosamente.');
    }

    /**
     * Elimina un registro específico.
     *
     * @param  \App\Models\VacunasCertificacione  $vacunasCertificacione
     * @return \Illuminate\Http\Response
     */
    public function destroy(VacunasCertificacione $vacunasCertificacione)
    {
        // Eliminar los archivos si existen
        if ($vacunasCertificacione->certificado_veterinario) {
            Storage::disk('public')->delete($vacunasCertificacione->certificado_veterinario);
        }
        if ($vacunasCertificacione->cedula_propietario) {
            Storage::disk('public')->delete($vacunasCertificacione->cedula_propietario);
        }

        $vacunasCertificacione->delete();

        return redirect()->route('vacunas_certificaciones.index')
            ->with('success', 'Registro de vacunas y certificaciones eliminado exitosamente.');
    }
}

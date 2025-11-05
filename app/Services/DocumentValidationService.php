<?php

namespace App\Services;

use App\Models\DocumentRequirement;
use App\Models\MascotaDocument;
use App\Models\Mascota;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DocumentValidationService
{
    /**
     * Validar un documento subido
     */
    public function validarDocumento(
        Mascota $mascota,
        DocumentRequirement $requirement,
        UploadedFile $archivo,
        ?Carbon $fechaEmision = null,
        ?Carbon $fechaVencimiento = null
    ): array {
        $resultado = [
            'valido' => true,
            'errores' => [],
            'advertencias' => [],
            'detalles' => [],
        ];

        // 1. Validar formato
        $validacionFormato = $this->validarFormato($archivo, $requirement);
        if (!$validacionFormato['valido']) {
            $resultado['valido'] = false;
            $resultado['errores'] = array_merge($resultado['errores'], $validacionFormato['errores']);
        }

        // 2. Validar tamaño
        $validacionTamaño = $this->validarTamaño($archivo, $requirement);
        if (!$validacionTamaño['valido']) {
            $resultado['valido'] = false;
            $resultado['errores'] = array_merge($resultado['errores'], $validacionTamaño['errores']);
        }

        // 3. Validar fechas
        if ($requirement->tipo_validacion === 'fecha_vencimiento' && $requirement->dias_validez) {
            $validacionFechas = $this->validarFechas($fechaEmision, $fechaVencimiento, $requirement);
            if (!$validacionFechas['valido']) {
                $resultado['valido'] = false;
                $resultado['errores'] = array_merge($resultado['errores'], $validacionFechas['errores']);
            }
            if (!empty($validacionFechas['advertencias'])) {
                $resultado['advertencias'] = array_merge($resultado['advertencias'], $validacionFechas['advertencias']);
            }
            $resultado['detalles'] = array_merge($resultado['detalles'], $validacionFechas['detalles'] ?? []);
        }

        // 4. Validar contenido (básico - se puede extender con OCR)
        $validacionContenido = $this->validarContenidoBasico($archivo, $requirement);
        if (!$validacionContenido['valido']) {
            $resultado['valido'] = false;
            $resultado['errores'] = array_merge($resultado['errores'], $validacionContenido['errores']);
        }

        // Guardar detalles de validación
        $resultado['detalles'] = array_merge($resultado['detalles'], [
            'formato' => $archivo->getMimeType(),
            'tamaño' => $archivo->getSize(),
            'fecha_validacion' => now()->toDateTimeString(),
        ]);

        return $resultado;
    }

    /**
     * Validar formato del archivo
     */
    protected function validarFormato(UploadedFile $archivo, DocumentRequirement $requirement): array
    {
        $resultado = ['valido' => true, 'errores' => []];

        $formatosPermitidos = $requirement->formatos_permitidos ?? ['pdf', 'jpg', 'jpeg', 'png'];
        $extension = strtolower($archivo->getClientOriginalExtension());
        $mimeType = $archivo->getMimeType();

        // Validar extensión
        if (!in_array($extension, $formatosPermitidos)) {
            $resultado['valido'] = false;
            $resultado['errores'][] = "El formato del archivo ({$extension}) no está permitido. Formatos permitidos: " . implode(', ', $formatosPermitidos);
        }

        // Validar MIME type
        $mimePermitidos = [
            'pdf' => ['application/pdf'],
            'jpg' => ['image/jpeg'],
            'jpeg' => ['image/jpeg'],
            'png' => ['image/png'],
        ];

        if (isset($mimePermitidos[$extension]) && !in_array($mimeType, $mimePermitidos[$extension])) {
            $resultado['valido'] = false;
            $resultado['errores'][] = "El tipo MIME del archivo no coincide con la extensión.";
        }

        return $resultado;
    }

    /**
     * Validar tamaño del archivo
     */
    protected function validarTamaño(UploadedFile $archivo, DocumentRequirement $requirement): array
    {
        $resultado = ['valido' => true, 'errores' => []];

        $tamañoMaximo = ($requirement->tamaño_maximo_kb ?? 2048) * 1024; // Convertir a bytes
        $tamañoArchivo = $archivo->getSize();

        if ($tamañoArchivo > $tamañoMaximo) {
            $resultado['valido'] = false;
            $tamañoMaximoMB = round($tamañoMaximo / 1024 / 1024, 2);
            $tamañoArchivoMB = round($tamañoArchivo / 1024 / 1024, 2);
            $resultado['errores'][] = "El archivo es demasiado grande ({$tamañoArchivoMB} MB). Tamaño máximo permitido: {$tamañoMaximoMB} MB";
        }

        return $resultado;
    }

    /**
     * Validar fechas de emisión y vencimiento
     */
    protected function validarFechas(?Carbon $fechaEmision, ?Carbon $fechaVencimiento, DocumentRequirement $requirement): array
    {
        $resultado = ['valido' => true, 'errores' => [], 'advertencias' => [], 'detalles' => []];

        // Validar fecha de vencimiento
        if ($fechaVencimiento) {
            // Verificar que no esté vencido
            if ($fechaVencimiento->isPast()) {
                $resultado['valido'] = false;
                $resultado['errores'][] = "El documento está vencido. Fecha de vencimiento: {$fechaVencimiento->format('d/m/Y')}";
            }

            // Advertencia si está próximo a vencer (30 días)
            if ($fechaVencimiento->isFuture() && $fechaVencimiento->diffInDays(now()) <= 30) {
                $diasRestantes = $fechaVencimiento->diffInDays(now());
                $resultado['advertencias'][] = "El documento vence en {$diasRestantes} días ({$fechaVencimiento->format('d/m/Y')})";
            }

            // Validar que la fecha de vencimiento sea posterior a la de emisión
            if ($fechaEmision && $fechaVencimiento->isBefore($fechaEmision)) {
                $resultado['valido'] = false;
                $resultado['errores'][] = "La fecha de vencimiento no puede ser anterior a la fecha de emisión.";
            }

            // Validar días de validez según el requisito
            if ($requirement->dias_validez && $fechaEmision) {
                $diasTranscurridos = $fechaEmision->diffInDays(now());
                if ($diasTranscurridos > $requirement->dias_validez) {
                    $resultado['valido'] = false;
                    $resultado['errores'][] = "El documento excede los días de validez permitidos ({$requirement->dias_validez} días).";
                }
            }

            $resultado['detalles']['fecha_vencimiento'] = $fechaVencimiento->format('Y-m-d');
        }

        if ($fechaEmision) {
            $resultado['detalles']['fecha_emision'] = $fechaEmision->format('Y-m-d');
        }

        return $resultado;
    }

    /**
     * Validación básica de contenido (extensible con OCR)
     */
    protected function validarContenidoBasico(UploadedFile $archivo, DocumentRequirement $requirement): array
    {
        $resultado = ['valido' => true, 'errores' => []];

        // Verificar que el archivo no esté corrupto
        if ($archivo->getError() !== UPLOAD_ERR_OK) {
            $resultado['valido'] = false;
            $resultado['errores'][] = "Error al subir el archivo: " . $archivo->getErrorMessage();
        }

        // Para PDFs, verificar que sea un PDF válido
        if ($archivo->getClientOriginalExtension() === 'pdf') {
            $contenido = file_get_contents($archivo->getRealPath());
            if (strpos($contenido, '%PDF') !== 0) {
                $resultado['valido'] = false;
                $resultado['errores'][] = "El archivo PDF no es válido o está corrupto.";
            }
        }

        // TODO: Implementar validación OCR para detectar firmas, sellos, etc.
        // if ($requirement->tipo_validacion === 'firma_digital' || $requirement->tipo_validacion === 'sello_veterinario') {
        //     // Lógica de OCR aquí
        // }

        return $resultado;
    }

    /**
     * Almacenar documento y calcular hash
     */
    public function almacenarDocumento(
        Mascota $mascota,
        DocumentRequirement $requirement,
        UploadedFile $archivo,
        ?int $userId = null
    ): array {
        // Generar ruta única
        $rutaBase = "documentos_mascotas/{$mascota->id}/{$requirement->codigo}";
        $nombreArchivo = time() . '_' . $mascota->id . '_' . $requirement->codigo . '.' . $archivo->getClientOriginalExtension();
        $rutaCompleta = $rutaBase . '/' . $nombreArchivo;

        // Almacenar archivo
        $archivo->storeAs($rutaBase, $nombreArchivo, 'public');

        // Calcular hash SHA-256
        $contenido = file_get_contents($archivo->getRealPath());
        $hash = hash('sha256', $contenido);

        return [
            'ruta_archivo' => $rutaCompleta,
            'nombre_archivo' => $archivo->getClientOriginalName(),
            'tipo_mime' => $archivo->getMimeType(),
            'tamaño_bytes' => $archivo->getSize(),
            'hash_archivo' => $hash,
        ];
    }

    /**
     * Obtener requisitos activos para una mascota
     */
    public function obtenerRequisitosActivos(Mascota $mascota): \Illuminate\Database\Eloquent\Collection
    {
        $requisitos = DocumentRequirement::activos()
            ->ordenados()
            ->get();

        // Filtrar por raza si aplica
        return $requisitos->filter(function ($requisito) use ($mascota) {
            return $requisito->aplicaParaRaza($mascota->raza);
        });
    }
}


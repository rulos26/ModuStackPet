<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeocodingService
{
    /**
     * API de Nominatim (OpenStreetMap) - Gratuita y sin API key
     */
    private const NOMINATIM_API_URL = 'https://nominatim.openstreetmap.org/search';

    /**
     * Geocodificar una dirección y obtener latitud y longitud
     *
     * @param string $direccion Dirección completa a geocodificar
     * @param string|null $ciudad Nombre de la ciudad (opcional, mejora la precisión)
     * @param string|null $pais País (opcional, por defecto Colombia)
     * @return array|null Array con 'latitud' y 'longitud', o null si falla
     */
    public function geocode(string $direccion, ?string $ciudad = null, ?string $pais = 'Colombia'): ?array
    {
        try {
            // Construir la dirección completa
            $direccionCompleta = $direccion;
            if ($ciudad) {
                $direccionCompleta .= ', ' . $ciudad;
            }
            if ($pais) {
                $direccionCompleta .= ', ' . $pais;
            }

            // Construir URL completa para logging
            $urlCompleta = self::NOMINATIM_API_URL . '?q=' . urlencode($direccionCompleta) . '&format=json&limit=1&addressdetails=1&countrycodes=co';
            
            Log::info('DEBUG GeocodingService: Realizando petición HTTP', [
                'url' => $urlCompleta,
                'direccion_completa' => $direccionCompleta,
            ]);

            // Realizar petición a Nominatim
            $response = Http::timeout(10)
                ->withHeaders([
                    'User-Agent' => 'ModuStackPet/1.0 (Contact: ' . config('app.email', 'info@modustackpet.com') . ')',
                    'Accept-Language' => 'es,en'
                ])
                ->get(self::NOMINATIM_API_URL, [
                    'q' => $direccionCompleta,
                    'format' => 'json',
                    'limit' => 1,
                    'addressdetails' => 1,
                    'countrycodes' => 'co', // Solo Colombia
                ]);

            Log::info('DEBUG GeocodingService: Respuesta HTTP recibida', [
                'status' => $response->status(),
                'successful' => $response->successful(),
                'body_preview' => substr($response->body(), 0, 200),
            ]);

            if (!$response->successful()) {
                Log::warning('Error en geocodificación: Respuesta no exitosa', [
                    'direccion' => $direccion,
                    'status' => $response->status(),
                ]);
                return null;
            }

            $data = $response->json();

            // Si no hay resultados, intentar variaciones de la dirección
            if (empty($data) || !isset($data[0])) {
                Log::info('DEBUG GeocodingService: No se encontraron resultados, intentando variaciones', [
                    'direccion_original' => $direccion,
                    'direccion_completa' => $direccionCompleta,
                ]);
                
                // Intentar variaciones de la dirección
                $variaciones = $this->generarVariacionesDireccion($direccion, $ciudad, $pais);
                
                foreach ($variaciones as $variacion) {
                    Log::info('DEBUG GeocodingService: Intentando variación', [
                        'variacion' => $variacion,
                    ]);
                    
                    $responseVariacion = Http::timeout(10)
                        ->withHeaders([
                            'User-Agent' => 'ModuStackPet/1.0 (Contact: ' . config('app.email', 'info@modustackpet.com') . ')',
                            'Accept-Language' => 'es,en'
                        ])
                        ->get(self::NOMINATIM_API_URL, [
                            'q' => $variacion,
                            'format' => 'json',
                            'limit' => 1,
                            'addressdetails' => 1,
                            'countrycodes' => 'co',
                        ]);
                    
                    if ($responseVariacion->successful()) {
                        $dataVariacion = $responseVariacion->json();
                        if (!empty($dataVariacion) && isset($dataVariacion[0])) {
                            Log::info('DEBUG GeocodingService: Variación exitosa', [
                                'variacion' => $variacion,
                                'resultado' => $dataVariacion[0],
                            ]);
                            $data = $dataVariacion;
                            break;
                        }
                    }
                    
                    // Pequeña pausa entre intentos para no sobrecargar la API
                    usleep(500000); // 0.5 segundos
                }
                
                // Si aún no hay resultados después de todas las variaciones
                if (empty($data) || !isset($data[0])) {
                    Log::warning('Error en geocodificación: No se encontraron resultados después de todas las variaciones', [
                        'direccion' => $direccion,
                        'variaciones_intentadas' => count($variaciones),
                    ]);
                    return null;
                }
            }

            $resultado = $data[0];

            if (!isset($resultado['lat']) || !isset($resultado['lon'])) {
                Log::warning('Error en geocodificación: Coordenadas no encontradas', [
                    'direccion' => $direccion,
                    'resultado' => $resultado,
                ]);
                return null;
            }

            return [
                'latitud' => (float) $resultado['lat'],
                'longitud' => (float) $resultado['lon'],
            ];

        } catch (\Exception $e) {
            Log::error('Error en geocodificación: Excepción', [
                'direccion' => $direccion,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return null;
        }
    }

    /**
     * Geocodificar dirección específica de Bogotá, Engativá
     *
     * @param string $direccion Dirección en Engativá
     * @return array|null
     */
    public function geocodeEngativa(string $direccion): ?array
    {
        Log::info('DEBUG GeocodingService: Iniciando geocodeEngativa', [
            'direccion' => $direccion,
            'direccion_completa' => $direccion . ', Engativá, Bogotá, Colombia',
        ]);
        
        $resultado = $this->geocode($direccion, 'Engativá, Bogotá', 'Colombia');
        
        Log::info('DEBUG GeocodingService: Resultado de geocodeEngativa', [
            'direccion' => $direccion,
            'resultado' => $resultado,
            'es_array' => is_array($resultado),
            'no_null' => !is_null($resultado),
        ]);
        
        return $resultado;
    }

    /**
     * Generar variaciones de una dirección para mejorar las búsquedas
     *
     * @param string $direccion
     * @param string|null $ciudad
     * @param string|null $pais
     * @return array
     */
    private function generarVariacionesDireccion(string $direccion, ?string $ciudad = null, ?string $pais = 'Colombia'): array
    {
        $variaciones = [];
        
        // Limpiar y normalizar la dirección
        $direccionLimpia = trim($direccion);
        
        // Variación 1: Dirección original completa
        $variaciones[] = $direccionLimpia . ($ciudad ? ', ' . $ciudad : '') . ($pais ? ', ' . $pais : '');
        
        // Variación 2: Sin símbolo # (reemplazar por "No" o "Número")
        $direccionSinNumeral = str_replace('#', 'No', $direccionLimpia);
        if ($direccionSinNumeral !== $direccionLimpia) {
            $variaciones[] = $direccionSinNumeral . ($ciudad ? ', ' . $ciudad : '') . ($pais ? ', ' . $pais : '');
        }
        
        // Variación 3: Reemplazar # por número
        $direccionConNumero = preg_replace('/#\s*/', 'número ', $direccionLimpia);
        if ($direccionConNumero !== $direccionLimpia) {
            $variaciones[] = $direccionConNumero . ($ciudad ? ', ' . $ciudad : '') . ($pais ? ', ' . $pais : '');
        }
        
        // Variación 4: Solo dirección + Engativá, Bogotá (específico para este caso)
        if (stripos($direccionLimpia, 'CRA') !== false || stripos($direccionLimpia, 'Carrera') !== false) {
            $variaciones[] = $direccionLimpia . ', Engativá, Bogotá, ' . $pais;
            $variaciones[] = $direccionLimpia . ', Engativa, Bogota, ' . $pais;
            // Agregar variación con "Carrera" en lugar de "CRA"
            $direccionConCarrera = str_ireplace('CRA', 'Carrera', $direccionLimpia);
            if ($direccionConCarrera !== $direccionLimpia) {
                $variaciones[] = $direccionConCarrera . ', Engativá, Bogotá, ' . $pais;
            }
        }
        
        // Variación 5: Solo dirección + Bogotá
        $variaciones[] = $direccionLimpia . ', Bogotá, ' . $pais;
        $variaciones[] = $direccionLimpia . ', Bogota, ' . $pais;
        
        // Variación 6: Solo dirección + Colombia
        $variaciones[] = $direccionLimpia . ', ' . $pais;
        
        // Variación 7: Solo Engativá, Bogotá (para buscar ubicación aproximada si no se encuentra la dirección exacta)
        if ($ciudad && (stripos($ciudad, 'Engativá') !== false || stripos($ciudad, 'Engativa') !== false)) {
            $variaciones[] = 'Engativá, Bogotá, ' . $pais;
            $variaciones[] = 'Engativa, Bogota, ' . $pais;
        }
        
        // Eliminar duplicados manteniendo el orden
        return array_unique($variaciones);
    }

    /**
     * Validar si las coordenadas son válidas
     *
     * @param float $latitud
     * @param float $longitud
     * @return bool
     */
    public function validarCoordenadas(float $latitud, float $longitud): bool
    {
        // Coordenadas válidas para Colombia aproximadamente
        return $latitud >= -4.0 && $latitud <= 12.0 &&
               $longitud >= -82.0 && $longitud <= -66.0;
    }
}


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

            if (empty($data) || !isset($data[0])) {
                Log::warning('Error en geocodificación: No se encontraron resultados', [
                    'direccion' => $direccion,
                ]);
                return null;
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


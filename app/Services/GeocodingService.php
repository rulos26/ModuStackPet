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
            // Normalizar y limpiar la dirección
            $direccionLimpia = trim($direccion);
            
            // Intentar parsear la dirección colombiana (CRA, CALLE, AV, etc.)
            $direccionParseada = $this->parsearDireccionColombiana($direccionLimpia);
            
            // Construir consulta estructurada si es posible
            $params = [
                'format' => 'json',
                'limit' => 1,
                'addressdetails' => 1,
                'countrycodes' => 'co', // Solo Colombia
                'extratags' => 1,
                'namedetails' => 1,
            ];
            
            // Si logramos parsear la dirección, usar formato estructurado
            if ($direccionParseada && isset($direccionParseada['street'])) {
                // Formato estructurado: mejor para Nominatim
                $params['street'] = $direccionParseada['street'];
                if (isset($direccionParseada['city'])) {
                    $params['city'] = $direccionParseada['city'];
                }
                if (isset($direccionParseada['state'])) {
                    $params['state'] = $direccionParseada['state'];
                }
                $params['country'] = $pais ?? 'Colombia';
                
                Log::info('DEBUG GeocodingService: Usando formato estructurado', [
                    'params' => $params,
                    'direccion_original' => $direccion,
                ]);
            } else {
                // Formato libre: concatenar dirección completa
                $direccionCompleta = $direccionLimpia;
                if ($ciudad) {
                    $direccionCompleta .= ', ' . $ciudad;
                }
                if ($pais) {
                    $direccionCompleta .= ', ' . $pais;
                }
                $params['q'] = $direccionCompleta;
                
                Log::info('DEBUG GeocodingService: Usando formato libre', [
                    'direccion_completa' => $direccionCompleta,
                ]);
            }
            
            // Construir URL completa para logging
            $urlCompleta = self::NOMINATIM_API_URL . '?' . http_build_query($params);
            
            Log::info('DEBUG GeocodingService: Realizando petición HTTP', [
                'url' => $urlCompleta,
                'params' => $params,
            ]);

            // Realizar petición a Nominatim
            $response = Http::timeout(10)
                ->withHeaders([
                    'User-Agent' => 'ModuStackPet/1.0 (Contact: ' . config('app.email', 'info@modustackpet.com') . ')',
                    'Accept-Language' => 'es,en'
                ])
                ->get(self::NOMINATIM_API_URL, $params);

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
                    'direccion_parseada' => $direccionParseada,
                ]);
                
                // Intentar variaciones de la dirección (formato libre)
                $variaciones = $this->generarVariacionesDireccion($direccion, $ciudad, $pais);
                
                foreach ($variaciones as $variacion) {
                    Log::info('DEBUG GeocodingService: Intentando variación (formato libre)', [
                        'variacion' => $variacion,
                    ]);
                    
                    $paramsVariacion = [
                        'q' => $variacion,
                        'format' => 'json',
                        'limit' => 1,
                        'addressdetails' => 1,
                        'countrycodes' => 'co',
                    ];
                    
                    $responseVariacion = Http::timeout(10)
                        ->withHeaders([
                            'User-Agent' => 'ModuStackPet/1.0 (Contact: ' . config('app.email', 'info@modustackpet.com') . ')',
                            'Accept-Language' => 'es,en'
                        ])
                        ->get(self::NOMINATIM_API_URL, $paramsVariacion);
                    
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
                    
                    // Pequeña pausa entre intentos para no sobrecargar la API (máximo 1 request por segundo)
                    usleep(1000000); // 1 segundo
                }
                
                // Si aún no hay resultados, intentar con formato estructurado alternativo
                if (empty($data) || !isset($data[0])) {
                    Log::info('DEBUG GeocodingService: Intentando formato estructurado alternativo', [
                        'direccion' => $direccion,
                    ]);
                    
                    // Intentar con formato estructurado simple
                    $paramsEstructurado = [
                        'street' => $direccionLimpia,
                        'city' => 'Bogotá',
                        'country' => 'Colombia',
                        'format' => 'json',
                        'limit' => 1,
                        'addressdetails' => 1,
                        'countrycodes' => 'co',
                    ];
                    
                    $responseEstructurado = Http::timeout(10)
                        ->withHeaders([
                            'User-Agent' => 'ModuStackPet/1.0 (Contact: ' . config('app.email', 'info@modustackpet.com') . ')',
                            'Accept-Language' => 'es,en'
                        ])
                        ->get(self::NOMINATIM_API_URL, $paramsEstructurado);
                    
                    if ($responseEstructurado->successful()) {
                        $dataEstructurado = $responseEstructurado->json();
                        if (!empty($dataEstructurado) && isset($dataEstructurado[0])) {
                            Log::info('DEBUG GeocodingService: Formato estructurado exitoso', [
                                'params' => $paramsEstructurado,
                                'resultado' => $dataEstructurado[0],
                            ]);
                            $data = $dataEstructurado;
                        }
                    }
                    
                    usleep(1000000); // 1 segundo
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
     * Parsear dirección colombiana en componentes estructurados
     * Ejemplo: "CRA 19 # 51-15" -> ['street' => 'Carrera 19 No 51-15', 'city' => 'Bogotá']
     *
     * @param string $direccion
     * @return array|null
     */
    private function parsearDireccionColombiana(string $direccion): ?array
    {
        $direccion = trim($direccion);
        if (empty($direccion)) {
            return null;
        }
        
        $resultado = [];
        
        // Normalizar abreviaciones comunes
        $patrones = [
            '/^CRA\s+(\d+)\s*#?\s*(\d+)[-\s]*(\d+)?/i' => 'Carrera $1 No $2-$3',
            '/^CARRERA\s+(\d+)\s*#?\s*(\d+)[-\s]*(\d+)?/i' => 'Carrera $1 No $2-$3',
            '/^CL\s+(\d+)\s*#?\s*(\d+)[-\s]*(\d+)?/i' => 'Calle $1 No $2-$3',
            '/^CALLE\s+(\d+)\s*#?\s*(\d+)[-\s]*(\d+)?/i' => 'Calle $1 No $2-$3',
            '/^AV\s+(\d+)\s*#?\s*(\d+)[-\s]*(\d+)?/i' => 'Avenida $1 No $2-$3',
            '/^AVENIDA\s+(\d+)\s*#?\s*(\d+)[-\s]*(\d+)?/i' => 'Avenida $1 No $2-$3',
            '/^KR\s+(\d+)\s*#?\s*(\d+)[-\s]*(\d+)?/i' => 'Carrera $1 No $2-$3',
            '/^KRA\s+(\d+)\s*#?\s*(\d+)[-\s]*(\d+)?/i' => 'Carrera $1 No $2-$3',
        ];
        
        $streetFormateada = $direccion;
        foreach ($patrones as $patron => $reemplazo) {
            if (preg_match($patron, $direccion, $matches)) {
                $streetFormateada = preg_replace($patron, $reemplazo, $direccion);
                // Limpiar espacios extras
                $streetFormateada = preg_replace('/\s+/', ' ', trim($streetFormateada));
                break;
            }
        }
        
        $resultado['street'] = $streetFormateada;
        
        // Si la ciudad está en la dirección, extraerla
        if (stripos($direccion, 'Bogotá') !== false || stripos($direccion, 'Bogota') !== false) {
            $resultado['city'] = 'Bogotá';
        }
        
        // Si menciona Engativá, agregarlo como distrito/localidad
        if (stripos($direccion, 'Engativá') !== false || stripos($direccion, 'Engativa') !== false) {
            $resultado['city'] = 'Bogotá';
            $resultado['suburb'] = 'Engativá';
        }
        
        return !empty($resultado) ? $resultado : null;
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


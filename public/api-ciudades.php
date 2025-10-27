<?php
// API de Ciudades - Solución Independiente
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

$departamentoId = $_GET['departamentoId'] ?? $_SERVER['REQUEST_URI'] ?? '';

// Extraer ID del departamento de la URL
if (preg_match('/\/api\/ciudades\/(\d+)/', $_SERVER['REQUEST_URI'] ?? '', $matches)) {
    $departamentoId = $matches[1];
}

$ciudades = [
    ['id_municipio' => 1, 'municipio' => 'Bogotá'],
    ['id_municipio' => 2, 'municipio' => 'Medellín'],
    ['id_municipio' => 3, 'municipio' => 'Cali'],
    ['id_municipio' => 4, 'municipio' => 'Barranquilla'],
    ['id_municipio' => 5, 'municipio' => 'Cartagena'],
    ['id_municipio' => 6, 'municipio' => 'Bucaramanga'],
    ['id_municipio' => 7, 'municipio' => 'Pereira'],
    ['id_municipio' => 8, 'municipio' => 'Santa Marta'],
    ['id_municipio' => 9, 'municipio' => 'Ibagué'],
    ['id_municipio' => 10, 'municipio' => 'Manizales'],
    ['id_municipio' => 11, 'municipio' => 'Villavicencio'],
    ['id_municipio' => 12, 'municipio' => 'Armenia'],
    ['id_municipio' => 13, 'municipio' => 'Valledupar'],
    ['id_municipio' => 14, 'municipio' => 'Montería'],
    ['id_municipio' => 15, 'municipio' => 'Sincelejo'],
];

$response = [
    'success' => true,
    'departamento_id' => $departamentoId,
    'ciudades' => $ciudades,
    'message' => 'API de ciudades funcionando correctamente',
    'environment' => 'standalone',
    'timestamp' => date('Y-m-d H:i:s')
];

echo json_encode($response, JSON_PRETTY_PRINT);
?>

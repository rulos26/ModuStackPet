<?php
// API de Ciudades - Solución Independiente
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

$departamentoId = $_GET['departamentoId'] ?? '11';

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
    ['id_municipio' => 16, 'municipio' => 'Neiva'],
    ['id_municipio' => 17, 'municipio' => 'Popayán'],
    ['id_municipio' => 18, 'municipio' => 'Tunja'],
    ['id_municipio' => 19, 'municipio' => 'Florencia'],
    ['id_municipio' => 20, 'municipio' => 'Yopal'],
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

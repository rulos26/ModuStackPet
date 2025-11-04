<?php

namespace App\Services;

use App\Models\User;
use App\Models\Mascota;

class ClienteDataVerificationService
{
    /**
     * Verificar qué datos faltan al cliente
     */
    public function getMissingData(User $user): array
    {
        $missingData = [];
        
        // Verificar email verificado
        if (!$user->hasVerifiedEmail()) {
            $missingData[] = [
                'type' => 'email_verification',
                'label' => 'Verificar correo electrónico',
                'description' => 'Debes verificar tu correo electrónico para continuar',
                'route' => 'verification.notice',
                'priority' => 1, // Máxima prioridad
                'icon' => 'fas fa-envelope',
                'completed' => false,
            ];
        }
        
        // Verificar datos principales del usuario
        $userData = [
            'telefono' => ['label' => 'Teléfono/Celular', 'icon' => 'fas fa-phone', 'route' => 'cliente.perfil.edit'],
            'whatsapp' => ['label' => 'WhatsApp', 'icon' => 'fab fa-whatsapp', 'route' => 'cliente.perfil.edit'],
            'cedula' => ['label' => 'Cédula', 'icon' => 'fas fa-id-card', 'route' => 'cliente.perfil.edit'],
            'fecha_nacimiento' => ['label' => 'Fecha de Nacimiento', 'icon' => 'fas fa-birthday-cake', 'route' => 'cliente.perfil.edit'],
            'avatar' => ['label' => 'Foto de Perfil', 'icon' => 'fas fa-user-circle', 'route' => 'cliente.perfil.edit'],
        ];
        
        foreach ($userData as $field => $data) {
            if (empty($user->$field)) {
                $missingData[] = [
                    'type' => 'user_data',
                    'field' => $field,
                    'label' => $data['label'],
                    'description' => "Te falta completar tu {$data['label']}",
                    'route' => $data['route'],
                    'route_params' => ['user' => $user->id],
                    'priority' => 2,
                    'icon' => $data['icon'],
                    'completed' => false,
                ];
            }
        }
        
        // Verificar datos del perfil Cliente
        $cliente = $user->cliente;
        if ($cliente) {
            $clienteData = [
                'nombre' => ['label' => 'Nombre Completo', 'icon' => 'fas fa-user'],
                'direccion' => ['label' => 'Dirección', 'icon' => 'fas fa-map-marker-alt'],
                'ciudad_id' => ['label' => 'Ciudad', 'icon' => 'fas fa-city'],
                'barrio_id' => ['label' => 'Barrio', 'icon' => 'fas fa-map'],
                'avatar' => ['label' => 'Foto de Perfil (Cliente)', 'icon' => 'fas fa-image'],
            ];
            
            foreach ($clienteData as $field => $data) {
                if (empty($cliente->$field)) {
                    $missingData[] = [
                        'type' => 'cliente_data',
                        'field' => $field,
                        'label' => $data['label'],
                        'description' => "Te falta completar tu {$data['label']} en el perfil",
                        'route' => 'cliente.perfil.edit',
                        'route_params' => ['user' => $user->id],
                        'priority' => 3,
                        'icon' => $data['icon'],
                        'completed' => false,
                    ];
                }
            }
        } else {
            // Si no existe el perfil de cliente, es un dato faltante importante
            $missingData[] = [
                'type' => 'cliente_profile',
                'label' => 'Perfil de Cliente',
                'description' => 'Debes completar tu perfil de cliente',
                'route' => 'cliente.perfil.edit',
                'route_params' => ['user' => $user->id],
                'priority' => 2,
                'icon' => 'fas fa-user-edit',
                'completed' => false,
            ];
        }
        
        // Verificar mascotas
        $mascotas = $user->mascotas;
        if ($mascotas->isEmpty()) {
            $missingData[] = [
                'type' => 'mascota_register',
                'label' => 'Registrar Mascota',
                'description' => 'Debes registrar al menos una mascota para continuar',
                'route' => 'mascotas.create',
                'priority' => 4,
                'icon' => 'fas fa-paw',
                'completed' => false,
            ];
        } else {
            // Verificar que las mascotas tengan foto
            foreach ($mascotas as $mascota) {
                if (empty($mascota->avatar)) {
                    $missingData[] = [
                        'type' => 'mascota_photo',
                        'label' => "Foto de {$mascota->nombre}",
                        'description' => "Te falta subir la foto de tu mascota {$mascota->nombre}",
                        'route' => 'mascotas.edit',
                        'route_params' => ['mascota' => $mascota->id],
                        'priority' => 5,
                        'icon' => 'fas fa-camera',
                        'completed' => false,
                    ];
                }
            }
        }
        
        // Ordenar por prioridad
        usort($missingData, function($a, $b) {
            return $a['priority'] <=> $b['priority'];
        });
        
        return $missingData;
    }
    
    /**
     * Calcular el porcentaje de completitud del perfil
     */
    public function getCompletionPercentage(User $user): int
    {
        $totalItems = 10; // Total de items a verificar
        $completedItems = $totalItems - count($this->getMissingData($user));
        
        return (int) round(($completedItems / $totalItems) * 100);
    }
    
    /**
     * Verificar si el perfil está completo
     */
    public function isProfileComplete(User $user): bool
    {
        $missingData = $this->getMissingData($user);
        
        // El perfil está completo solo si no hay datos faltantes críticos (prioridad 1-2)
        $criticalMissing = array_filter($missingData, function($item) {
            return $item['priority'] <= 2;
        });
        
        return empty($criticalMissing);
    }
}


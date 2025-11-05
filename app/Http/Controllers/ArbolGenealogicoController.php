<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mascota;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ArbolGenealogicoController extends Controller
{
    /**
     * Muestra el árbol genealógico del cliente autenticado con sus mascotas
     */
    public function index(): View
    {
        $user = Auth::user();
        
        // Cargar el cliente con sus mascotas
        $cliente = $user->cliente;
        
        // Obtener todas las mascotas del cliente
        $mascotas = Mascota::where('user_id', $user->id)
            ->with(['raza'])
            ->get();
        
        // Preparar datos para el árbol genealógico
        $arbolData = [
            'cliente' => [
                'id' => $user->id,
                'nombre' => $user->name,
                'email' => $user->email,
                'avatar' => $this->getAvatarUrl($user->avatar),
                'tipo' => 'cliente',
                'color' => '#1E40AF', // Azul vibrante
            ],
            'mascotas' => $mascotas->map(function ($mascota) {
                return [
                    'id' => $mascota->id,
                    'nombre' => $mascota->nombre,
                    'raza' => $mascota->raza->nombre ?? 'Sin raza',
                    'tipo_mascota' => $mascota->raza->tipo_mascota ?? 'Mascota',
                    'avatar' => $this->getMascotaAvatarUrl($mascota->avatar),
                    'edad' => $mascota->edad ?? 'N/A',
                    'genero' => $mascota->genero ?? 'No especificado',
                    'tipo' => 'mascota',
                    'color' => $this->getColorForMascota($mascota->raza->tipo_mascota ?? 'Mascota'),
                ];
            })->toArray(),
        ];
        
        return view('cliente.arbol-genealogico', [
            'arbolData' => $arbolData,
            'mascotas' => $mascotas,
        ]);
    }
    
    /**
     * Obtiene la URL del avatar del usuario
     */
    private function getAvatarUrl(?string $avatar): string
    {
        if (!$avatar) {
            return asset('public/storage/img/default.png');
        }
        
        if (strpos($avatar, 'storage/img/avatar/') === 0) {
            $filePath = public_path($avatar);
            if (file_exists($filePath)) {
                return asset('public/' . $avatar);
            }
            $fileName = basename($avatar);
            $altPath = public_path('storage/img/avatar/' . $fileName);
            if (file_exists($altPath)) {
                return asset('public/storage/img/avatar/' . $fileName);
            }
        } elseif (file_exists(public_path('storage/' . $avatar))) {
            return asset('storage/' . $avatar);
        } elseif (file_exists(public_path($avatar))) {
            return asset('public/' . $avatar);
        } elseif (\Illuminate\Support\Facades\Storage::disk('public')->exists($avatar)) {
            return \Illuminate\Support\Facades\Storage::disk('public')->url($avatar);
        }
        
        return asset('public/storage/img/default.png');
    }
    
    /**
     * Obtiene la URL del avatar de la mascota
     */
    private function getMascotaAvatarUrl(?string $avatar): string
    {
        if (!$avatar) {
            return asset('public/storage/img/default.png');
        }
        
        if (strpos($avatar, 'avatars/') === 0) {
            $filePath = public_path($avatar);
            if (file_exists($filePath)) {
                return asset('public/' . $avatar);
            }
        } elseif (strpos($avatar, 'storage/') === 0) {
            $filePath = public_path($avatar);
            if (file_exists($filePath)) {
                return asset('public/' . $avatar);
            }
        } elseif (file_exists(public_path('storage/' . $avatar))) {
            return asset('storage/' . $avatar);
        } elseif (file_exists(public_path($avatar))) {
            return asset('public/' . $avatar);
        } elseif (\Illuminate\Support\Facades\Storage::disk('public')->exists($avatar)) {
            return \Illuminate\Support\Facades\Storage::disk('public')->url($avatar);
        }
        
        return asset('public/storage/img/default.png');
    }
    
    /**
     * Obtiene un color según el tipo de mascota
     */
    private function getColorForMascota(string $tipoMascota): string
    {
        $colores = [
            'Perro' => '#10B981',      // Verde
            'Gato' => '#F59E0B',      // Naranja
            'Conejo' => '#EC4899',     // Rosa
            'Ave' => '#8B5CF6',        // Púrpura
            'Hamster' => '#EF4444',    // Rojo
            'Otro' => '#6B7280',       // Gris
        ];
        
        return $colores[$tipoMascota] ?? '#3B82F6'; // Azul por defecto
    }
}


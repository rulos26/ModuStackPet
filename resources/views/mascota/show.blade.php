@extends('layouts.app')

@section('template_title')
    {{ $mascota->nombre ?? __('Detalles de la Mascota') }}
@endsection

@section('content')
<div class="container-fluid py-4">
    <!-- Header con gradiente azul vibrante (estilo logo) -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-lg border-0" style="background: linear-gradient(135deg, #1E40AF 0%, #3B82F6 50%, #60A5FA 100%); border-radius: 20px; position: relative; overflow: hidden;">
                <!-- Patrón decorativo sutil -->
                <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
                <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.08); border-radius: 50%;"></div>
                
                <div class="card-body p-4" style="position: relative; z-index: 1;">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            @php
                                $avatarUrl = asset('public/storage/img/default.png');
                                if ($mascota->avatar) {
                                    // Verificar diferentes rutas posibles del avatar
                                    if (strpos($mascota->avatar, 'avatars/') === 0) {
                                        // Ruta: avatars/cedula/mascotas/nombre.ext
                                        $filePath = public_path($mascota->avatar);
                                        if (file_exists($filePath)) {
                                            $avatarUrl = asset('public/' . $mascota->avatar);
                                        }
                                    } elseif (strpos($mascota->avatar, 'storage/') === 0) {
                                        $filePath = public_path($mascota->avatar);
                                        if (file_exists($filePath)) {
                                            $avatarUrl = asset('public/' . $mascota->avatar);
                                        }
                                    } elseif (file_exists(public_path('storage/' . $mascota->avatar))) {
                                        $avatarUrl = asset('storage/' . $mascota->avatar);
                                    } elseif (file_exists(public_path($mascota->avatar))) {
                                        $avatarUrl = asset('public/' . $mascota->avatar);
                                    } elseif (\Illuminate\Support\Facades\Storage::disk('public')->exists($mascota->avatar)) {
                                        $avatarUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($mascota->avatar);
                                    }
                                }
                            @endphp
                            <img src="{{ $avatarUrl }}" 
                                 alt="Foto de {{ $mascota->nombre }}" 
                                 class="rounded-circle shadow-lg" 
                                 style="width: 120px; height: 120px; object-fit: cover; border: 5px solid white; box-shadow: 0 8px 20px rgba(0,0,0,0.3);">
                        </div>
                        <div class="col">
                            <h2 class="text-white mb-1" style="font-weight: 700; text-shadow: 2px 2px 4px rgba(0,0,0,0.3); font-size: 2rem;">
                                <i class="fas fa-paw"></i> {{ $mascota->nombre }}
                            </h2>
                            <p class="text-white mb-2" style="opacity: 0.95; font-size: 1.1rem; text-shadow: 1px 1px 2px rgba(0,0,0,0.2);">
                                <i class="fas fa-dog"></i> {{ $mascota->raza->tipo_mascota ?? __('Mascota') }} - {{ $mascota->raza->nombre ?? __('Sin raza especificada') }}
                            </p>
                            <div class="d-flex gap-2 flex-wrap">
                                <span class="badge px-3 py-2" style="font-size: 0.9rem; background: rgba(255,255,255,0.3); color: white; border: 1px solid rgba(255,255,255,0.4); backdrop-filter: blur(10px);">
                                    <i class="fas fa-user"></i> {{ $mascota->user->name ?? __('Sin propietario') }}
                                </span>
                                <span class="badge px-3 py-2" style="font-size: 0.9rem; background: rgba(255,255,255,0.3); color: white; border: 1px solid rgba(255,255,255,0.4); backdrop-filter: blur(10px);">
                                    <i class="fas fa-{{ $mascota->genero === 'Macho' ? 'mars' : 'venus' }}"></i> {{ $mascota->genero ?? __('No especificado') }}
                                </span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('mascotas.edit', $mascota->id) }}" class="btn btn-light btn-lg shadow-sm" style="font-weight: 600; backdrop-filter: blur(10px);">
                                <i class="fas fa-edit"></i> {{ __('Editar Mascota') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Información Básica -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-lg border-0 h-100" style="border-radius: 20px; overflow: hidden;">
                <div class="card-header text-white" style="background: linear-gradient(135deg, #1E40AF 0%, #3B82F6 100%); border: none;">
                    <h4 class="mb-0" style="font-weight: 700;">
                        <i class="fas fa-info-circle"></i> {{ __('Información Básica') }}
                    </h4>
                </div>
                <div class="card-body p-4">
                    <div class="info-item mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <div class="icon-box me-3" style="width: 50px; height: 50px; background: linear-gradient(135deg, #1E40AF 0%, #3B82F6 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-paw text-white fa-lg"></i>
                            </div>
                            <div class="flex-grow-1">
                                <small class="text-muted d-block">{{ __('Raza') }}</small>
                                <strong class="text-dark">{{ $mascota->raza->nombre ?? __('No especificada') }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="info-item mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <div class="icon-box me-3" style="width: 50px; height: 50px; background: linear-gradient(135deg, #8B5CF6 0%, #A78BFA 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-birthday-cake text-white fa-lg"></i>
                            </div>
                            <div class="flex-grow-1">
                                <small class="text-muted d-block">{{ __('Edad') }}</small>
                                <strong class="text-dark">
                                    @if($mascota->edad)
                                        {{ $mascota->edad }} {{ $mascota->edad == 1 ? __('año') : __('años') }}
                                    @else
                                        {{ __('No especificada') }}
                                    @endif
                                </strong>
                            </div>
                        </div>
                    </div>

                    <div class="info-item mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <div class="icon-box me-3" style="width: 50px; height: 50px; background: linear-gradient(135deg, #3B82F6 0%, #60A5FA 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-calendar text-white fa-lg"></i>
                            </div>
                            <div class="flex-grow-1">
                                <small class="text-muted d-block">{{ __('Fecha de Nacimiento') }}</small>
                                <strong class="text-dark">
                                    @if($mascota->fecha_nacimiento)
                                        {{ $mascota->fecha_nacimiento->format('d/m/Y') }}
                                    @else
                                        {{ __('No especificada') }}
                                    @endif
                                </strong>
                            </div>
                        </div>
                    </div>

                    <div class="info-item mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <div class="icon-box me-3" style="width: 50px; height: 50px; background: linear-gradient(135deg, #EC4899 0%, #F472B6 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-{{ $mascota->genero === 'Macho' ? 'mars' : 'venus' }} text-white fa-lg"></i>
                            </div>
                            <div class="flex-grow-1">
                                <small class="text-muted d-block">{{ __('Género') }}</small>
                                <strong class="text-dark">{{ $mascota->genero ?? __('No especificado') }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="d-flex align-items-center mb-2">
                            <div class="icon-box me-3" style="width: 50px; height: 50px; background: linear-gradient(135deg, #10B981 0%, #34D399 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-user text-white fa-lg"></i>
                            </div>
                            <div class="flex-grow-1">
                                <small class="text-muted d-block">{{ __('Propietario') }}</small>
                                <strong class="text-dark">{{ $mascota->user->name ?? __('No especificado') }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información de Salud -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-lg border-0 h-100" style="border-radius: 20px; overflow: hidden;">
                <div class="card-header text-white" style="background: linear-gradient(135deg, #10B981 0%, #34D399 100%); border: none;">
                    <h4 class="mb-0" style="font-weight: 700;">
                        <i class="fas fa-heartbeat"></i> {{ __('Información de Salud') }}
                    </h4>
                </div>
                <div class="card-body p-4">
                    <div class="info-item mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <div class="icon-box me-3" style="width: 50px; height: 50px; background: linear-gradient(135deg, #10B981 0%, #34D399 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-syringe text-white fa-lg"></i>
                            </div>
                            <div class="flex-grow-1">
                                <small class="text-muted d-block">{{ __('Vacunas Completas') }}</small>
                                <strong class="text-dark">
                                    @if($mascota->vacunas_completas)
                                        <span class="badge" style="background: linear-gradient(135deg, #10B981 0%, #34D399 100%); color: white; border: none;">{{ __('Sí') }}</span>
                                    @else
                                        <span class="badge" style="background: linear-gradient(135deg, #EF4444 0%, #F87171 100%); color: white; border: none;">{{ __('No') }}</span>
                                    @endif
                                </strong>
                            </div>
                        </div>
                    </div>

                    <div class="info-item mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <div class="icon-box me-3" style="width: 50px; height: 50px; background: linear-gradient(135deg, #3B82F6 0%, #60A5FA 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-calendar-check text-white fa-lg"></i>
                            </div>
                            <div class="flex-grow-1">
                                <small class="text-muted d-block">{{ __('Última Vacunación') }}</small>
                                <strong class="text-dark">
                                    @if($mascota->ultima_vacunacion)
                                        {{ $mascota->ultima_vacunacion->format('d/m/Y') }}
                                    @else
                                        {{ __('No especificada') }}
                                    @endif
                                </strong>
                            </div>
                        </div>
                    </div>

                    <div class="info-item mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <div class="icon-box me-3" style="width: 50px; height: 50px; background: linear-gradient(135deg, #8B5CF6 0%, #A78BFA 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-cut text-white fa-lg"></i>
                            </div>
                            <div class="flex-grow-1">
                                <small class="text-muted d-block">{{ __('Esterilizado') }}</small>
                                <strong class="text-dark">
                                    @if($mascota->esterilizado)
                                        <span class="badge" style="background: linear-gradient(135deg, #10B981 0%, #34D399 100%); color: white; border: none;">{{ __('Sí') }}</span>
                                    @else
                                        <span class="badge" style="background: linear-gradient(135deg, #EF4444 0%, #F87171 100%); color: white; border: none;">{{ __('No') }}</span>
                                    @endif
                                </strong>
                            </div>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="d-flex align-items-center mb-2">
                            <div class="icon-box me-3" style="width: 50px; height: 50px; background: linear-gradient(135deg, #1E40AF 0%, #3B82F6 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-stethoscope text-white fa-lg"></i>
                            </div>
                            <div class="flex-grow-1">
                                <small class="text-muted d-block">{{ __('Último Examen Médico') }}</small>
                                <strong class="text-dark">
                                    @if($mascota->ultimo_examen_medico)
                                        {{ $mascota->ultimo_examen_medico->format('d/m/Y') }}
                                    @else
                                        {{ __('No especificado') }}
                                    @endif
                                </strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Información Adicional -->
        <div class="col-lg-12 mb-4">
            <div class="card shadow-lg border-0" style="border-radius: 20px; overflow: hidden;">
                <div class="card-header text-white" style="background: linear-gradient(135deg, #8B5CF6 0%, #A78BFA 100%); border: none;">
                    <h4 class="mb-0" style="font-weight: 700;">
                        <i class="fas fa-file-medical"></i> {{ __('Información Adicional') }}
                    </h4>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="info-item">
                                <div class="d-flex align-items-start mb-2">
                                    <div class="icon-box me-3" style="width: 50px; height: 50px; background: linear-gradient(135deg, #F59E0B 0%, #FBBF24 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        <i class="fas fa-brain text-white fa-lg"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <small class="text-muted d-block mb-2">{{ __('Comportamiento') }}</small>
                                        <strong class="text-dark d-block" style="line-height: 1.6;">
                                            {{ $mascota->comportamiento ?? __('No especificado') }}
                                        </strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="info-item">
                                <div class="d-flex align-items-start mb-2">
                                    <div class="icon-box me-3" style="width: 50px; height: 50px; background: linear-gradient(135deg, #10B981 0%, #34D399 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        <i class="fas fa-lightbulb text-white fa-lg"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <small class="text-muted d-block mb-2">{{ __('Recomendaciones') }}</small>
                                        <strong class="text-dark d-block" style="line-height: 1.6;">
                                            {{ $mascota->recomendaciones ?? __('No especificadas') }}
                                        </strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="info-item">
                                <div class="d-flex align-items-start mb-2">
                                    <div class="icon-box me-3" style="width: 50px; height: 50px; background: linear-gradient(135deg, #EF4444 0%, #F87171 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        <i class="fas fa-exclamation-triangle text-white fa-lg"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <small class="text-muted d-block mb-2">{{ __('Enfermedades o Condiciones Médicas') }}</small>
                                        <strong class="text-dark d-block" style="line-height: 1.6;">
                                            {{ $mascota->enfermedades ?? __('No especificadas') }}
                                        </strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Botones de Acción -->
    <div class="row mt-4">
        <div class="col-12 text-center">
            <a href="{{ route('mascotas.index') }}" class="btn btn-lg px-5 shadow-sm me-2" style="background: linear-gradient(135deg, #1E40AF 0%, #3B82F6 100%); color: white; font-weight: 600; border: none;">
                <i class="fas fa-arrow-left"></i> {{ __('Volver a la Lista') }}
            </a>
            <a href="{{ route('mascotas.edit', $mascota->id) }}" class="btn btn-lg px-5 shadow-sm" style="background: linear-gradient(135deg, #10B981 0%, #34D399 100%); color: white; font-weight: 600; border: none;">
                <i class="fas fa-edit"></i> {{ __('Editar Mascota') }}
            </a>
        </div>
    </div>
</div>

<style>
    .info-item {
        transition: transform 0.2s;
    }
    
    .info-item:hover {
        transform: translateX(5px);
    }
    
    .icon-box {
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s;
    }
    
    .icon-box:hover {
        transform: scale(1.1) rotate(5deg);
    }
    
    .card {
        transition: transform 0.3s, box-shadow 0.3s;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
    }
    
    .badge {
        transition: transform 0.2s;
    }
    
    .badge:hover {
        transform: scale(1.05);
    }
</style>
@endsection

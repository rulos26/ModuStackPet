@extends('layouts.app')

@section('template_title')
    {{ $user->name ?? __('Mi Perfil') }}
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
                                if ($user->avatar) {
                                    if (strpos($user->avatar, 'storage/img/avatar/') === 0) {
                                        $filePath = public_path($user->avatar);
                                        if (file_exists($filePath)) {
                                            $avatarUrl = asset('public/' . $user->avatar);
                                        } else {
                                            $fileName = basename($user->avatar);
                                            $altPath = public_path('storage/img/avatar/' . $fileName);
                                            if (file_exists($altPath)) {
                                                $avatarUrl = asset('public/storage/img/avatar/' . $fileName);
                                            }
                                        }
                                    } elseif (file_exists(public_path('storage/' . $user->avatar))) {
                                        $avatarUrl = asset('storage/' . $user->avatar);
                                    } elseif (file_exists(public_path($user->avatar))) {
                                        $avatarUrl = asset('public/' . $user->avatar);
                                    } elseif (\Illuminate\Support\Facades\Storage::disk('public')->exists($user->avatar)) {
                                        $avatarUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($user->avatar);
                                    }
                                }
                            @endphp
                            <img src="{{ $avatarUrl }}" 
                                 alt="Foto de Perfil" 
                                 class="rounded-circle shadow-lg" 
                                 style="width: 120px; height: 120px; object-fit: cover; border: 5px solid white; box-shadow: 0 8px 20px rgba(0,0,0,0.3);">
                        </div>
                        <div class="col">
                            <h2 class="text-white mb-1" style="font-weight: 700; text-shadow: 2px 2px 4px rgba(0,0,0,0.3); font-size: 2rem;">
                                <i class="fas fa-user-circle"></i> {{ $user->name }}
                            </h2>
                            <p class="text-white mb-2" style="opacity: 0.95; font-size: 1.1rem; text-shadow: 1px 1px 2px rgba(0,0,0,0.2);">
                                <i class="fas fa-envelope"></i> {{ $user->email }}
                            </p>
                            <div class="d-flex gap-2 flex-wrap">
                                <span class="badge px-3 py-2" style="font-size: 0.9rem; background: rgba(255,255,255,0.3); color: white; border: 1px solid rgba(255,255,255,0.4); backdrop-filter: blur(10px);">
                                    <i class="fas fa-{{ $user->activo ? 'check-circle' : 'times-circle' }}"></i> 
                                    {{ $user->activo ? __('Activo') : __('Inactivo') }}
                                </span>
                                @if($user->cliente)
                                    <span class="badge px-3 py-2" style="font-size: 0.9rem; background: rgba(255,255,255,0.3); color: white; border: 1px solid rgba(255,255,255,0.4); backdrop-filter: blur(10px);">
                                        <i class="fas fa-paw"></i> {{ __('Cliente') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('cliente.perfil.edit', $user->id) }}" class="btn btn-light btn-lg shadow-sm" style="font-weight: 600; backdrop-filter: blur(10px);">
                                <i class="fas fa-edit"></i> {{ __('Editar Perfil') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Información Personal -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-lg border-0 h-100" style="border-radius: 20px; overflow: hidden;">
                <div class="card-header text-white" style="background: linear-gradient(135deg, #1E40AF 0%, #3B82F6 100%); border: none;">
                    <h4 class="mb-0" style="font-weight: 700;">
                        <i class="fas fa-user-circle"></i> {{ __('Información Personal') }}
                    </h4>
                </div>
                <div class="card-body p-4">
                    <div class="info-item mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <div class="icon-box me-3" style="width: 50px; height: 50px; background: linear-gradient(135deg, #1E40AF 0%, #3B82F6 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-id-card text-white fa-lg"></i>
                            </div>
                            <div class="flex-grow-1">
                                <small class="text-muted d-block">{{ __('Tipo de Documento') }}</small>
                                <strong class="text-dark">
                                    @php
                                        $tipoDocNombre = null;
                                        if ($user->cliente && $user->cliente->tipoDocumento) {
                                            $tipoDocNombre = $user->cliente->tipoDocumento->nombre;
                                        } elseif ($user->tipo_documento) {
                                            $tipo = \App\Models\TipoDocumento::find($user->tipo_documento);
                                            $tipoDocNombre = $tipo ? $tipo->nombre : null;
                                        }
                                    @endphp
                                    {{ $tipoDocNombre ?? __('No especificado') }}
                                </strong>
                            </div>
                        </div>
                    </div>

                    <div class="info-item mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <div class="icon-box me-3" style="width: 50px; height: 50px; background: linear-gradient(135deg, #8B5CF6 0%, #A78BFA 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-id-badge text-white fa-lg"></i>
                            </div>
                            <div class="flex-grow-1">
                                <small class="text-muted d-block">{{ __('Cédula') }}</small>
                                <strong class="text-dark">{{ $user->cedula ?? __('No especificada') }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="info-item mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <div class="icon-box me-3" style="width: 50px; height: 50px; background: linear-gradient(135deg, #3B82F6 0%, #60A5FA 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-birthday-cake text-white fa-lg"></i>
                            </div>
                            <div class="flex-grow-1">
                                <small class="text-muted d-block">{{ __('Fecha de Nacimiento') }}</small>
                                <strong class="text-dark">
                                    @if($user->fecha_nacimiento)
                                        {{ $user->fecha_nacimiento instanceof \Carbon\Carbon ? $user->fecha_nacimiento->format('d/m/Y') : $user->fecha_nacimiento }}
                                    @else
                                        {{ __('No especificada') }}
                                    @endif
                                </strong>
                            </div>
                        </div>
                    </div>

                    <div class="info-item mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <div class="icon-box me-3" style="width: 50px; height: 50px; background: linear-gradient(135deg, #10B981 0%, #34D399 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-phone text-white fa-lg"></i>
                            </div>
                            <div class="flex-grow-1">
                                <small class="text-muted d-block">{{ __('Teléfono') }}</small>
                                <strong class="text-dark">{{ $user->telefono ?? __('No especificado') }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="d-flex align-items-center mb-2">
                            <div class="icon-box me-3" style="width: 50px; height: 50px; background: linear-gradient(135deg, #25D366 0%, #128C7E 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fab fa-whatsapp text-white fa-lg"></i>
                            </div>
                            <div class="flex-grow-1">
                                <small class="text-muted d-block">{{ __('WhatsApp') }}</small>
                                <strong class="text-dark">{{ $user->whatsapp ?? __('No especificado') }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información de Ubicación -->
        @if($user->cliente)
        <div class="col-lg-6 mb-4">
            <div class="card shadow-lg border-0 h-100" style="border-radius: 20px; overflow: hidden;">
                <div class="card-header text-white" style="background: linear-gradient(135deg, #1E40AF 0%, #60A5FA 100%); border: none;">
                    <h4 class="mb-0" style="font-weight: 700;">
                        <i class="fas fa-map-marker-alt"></i> {{ __('Información de Ubicación') }}
                    </h4>
                </div>
                <div class="card-body p-4">
                    <!-- Ciudad (Primero) -->
                    <div class="info-item mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <div class="icon-box me-3" style="width: 50px; height: 50px; background: linear-gradient(135deg, #3B82F6 0%, #60A5FA 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-city text-white fa-lg"></i>
                            </div>
                            <div class="flex-grow-1">
                                <small class="text-muted d-block">{{ __('Ciudad') }}</small>
                                <strong class="text-dark">
                                    @if($user->cliente && $user->cliente->ciudad)
                                        {{ $user->cliente->ciudad->municipio }}
                                    @else
                                        {{ __('No especificada') }}
                                    @endif
                                </strong>
                            </div>
                        </div>
                    </div>

                    <!-- Barrio (Segundo) -->
                    <div class="info-item mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <div class="icon-box me-3" style="width: 50px; height: 50px; background: linear-gradient(135deg, #1E40AF 0%, #3B82F6 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-map text-white fa-lg"></i>
                            </div>
                            <div class="flex-grow-1">
                                <small class="text-muted d-block">{{ __('Barrio') }}</small>
                                <strong class="text-dark">
                                    @if($user->cliente && $user->cliente->barrio)
                                        {{ $user->cliente->barrio->nombre }}
                                    @else
                                        {{ __('No especificado') }}
                                    @endif
                                </strong>
                            </div>
                        </div>
                    </div>

                    <!-- Dirección (Tercero) -->
                    <div class="info-item mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <div class="icon-box me-3" style="width: 50px; height: 50px; background: linear-gradient(135deg, #1E40AF 0%, #3B82F6 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-map-pin text-white fa-lg"></i>
                            </div>
                            <div class="flex-grow-1">
                                <small class="text-muted d-block">{{ __('Dirección Completa') }}</small>
                                <strong class="text-dark">{{ $user->cliente->direccion ?? __('No especificada') }}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Nombre del Conjunto/Cerrado (Cuarto) -->
                    <div class="info-item mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <div class="icon-box me-3" style="width: 50px; height: 50px; background: linear-gradient(135deg, #8B5CF6 0%, #A78BFA 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-building text-white fa-lg"></i>
                            </div>
                            <div class="flex-grow-1">
                                <small class="text-muted d-block">{{ __('Nombre del Conjunto/Cerrado') }}</small>
                                <strong class="text-dark">{{ $user->cliente->nombre_conjunto_cerrado ?? __('No especificado') }}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Interior/Apartamento (Quinto) -->
                    <div class="info-item">
                        <div class="d-flex align-items-center mb-2">
                            <div class="icon-box me-3" style="width: 50px; height: 50px; background: linear-gradient(135deg, #F59E0B 0%, #FBBF24 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-door-open text-white fa-lg"></i>
                            </div>
                            <div class="flex-grow-1">
                                <small class="text-muted d-block">{{ __('Interior/Apartamento') }}</small>
                                <strong class="text-dark">{{ $user->cliente->interior_apartamento ?? __('No especificado') }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Botón de acción -->
    <div class="row mt-4">
        <div class="col-12 text-center">
            <a href="{{ route('cliente.dashboard') }}" class="btn btn-lg px-5 shadow-sm" style="background: linear-gradient(135deg, #1E40AF 0%, #3B82F6 100%); color: white; font-weight: 600; border: none;">
                <i class="fas fa-arrow-left"></i> {{ __('Volver al Dashboard') }}
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

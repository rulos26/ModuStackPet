@extends('layouts.app')

@section('template_title')
    {{ $user->name ?? __('Mi Perfil') }}
@endsection

@section('content')
<div class="container-fluid py-4">
    <!-- Header con estilo del logo (azul vibrante) -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-lg border-0" style="background: #1E40AF; border-radius: 20px; position: relative; overflow: hidden;">
                <!-- Patrón decorativo sutil -->
                <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
                <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.03); border-radius: 50%;"></div>
                
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
                            <p class="text-white mb-2" style="opacity: 0.95; font-size: 1.1rem;">
                                <i class="fas fa-envelope"></i> {{ $user->email }}
                            </p>
                            <div class="d-flex gap-2 flex-wrap">
                                <span class="badge px-3 py-2" style="font-size: 0.9rem; background: rgba(255,255,255,0.25); color: white; border: 1px solid rgba(255,255,255,0.3);">
                                    <i class="fas fa-{{ $user->activo ? 'check-circle' : 'times-circle' }}"></i> 
                                    {{ $user->activo ? __('Activo') : __('Inactivo') }}
                                </span>
                                @if($user->cliente)
                                    <span class="badge px-3 py-2" style="font-size: 0.9rem; background: rgba(255,255,255,0.25); color: white; border: 1px solid rgba(255,255,255,0.3);">
                                        <i class="fas fa-paw"></i> {{ __('Cliente') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('cliente.perfil.edit', $user->id) }}" class="btn btn-light btn-lg shadow-sm" style="font-weight: 600;">
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
            <div class="card shadow-lg border-0 h-100" style="border-radius: 20px; overflow: hidden; border: 2px solid #E5E7EB;">
                <div class="card-header text-white" style="background: #1E40AF; border: none;">
                    <h4 class="mb-0" style="font-weight: 700;">
                        <i class="fas fa-user-circle"></i> {{ __('Información Personal') }}
                    </h4>
                </div>
                <div class="card-body p-4" style="background: #F9FAFB;">
                    <div class="info-item mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <div class="icon-box me-3" style="width: 55px; height: 55px; background: #1E40AF; border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(30, 64, 175, 0.3);">
                                <i class="fas fa-id-card text-white fa-lg"></i>
                            </div>
                            <div class="flex-grow-1">
                                <small class="text-muted d-block mb-1" style="font-weight: 600; color: #6B7280;">{{ __('Tipo de Documento') }}</small>
                                <strong class="text-dark" style="font-size: 1.05rem;">
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
                            <div class="icon-box me-3" style="width: 55px; height: 55px; background: #8B5CF6; border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);">
                                <i class="fas fa-id-badge text-white fa-lg"></i>
                            </div>
                            <div class="flex-grow-1">
                                <small class="text-muted d-block mb-1" style="font-weight: 600; color: #6B7280;">{{ __('Cédula') }}</small>
                                <strong class="text-dark" style="font-size: 1.05rem;">{{ $user->cedula ?? __('No especificada') }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="info-item mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <div class="icon-box me-3" style="width: 55px; height: 55px; background: #3B82F6; border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);">
                                <i class="fas fa-birthday-cake text-white fa-lg"></i>
                            </div>
                            <div class="flex-grow-1">
                                <small class="text-muted d-block mb-1" style="font-weight: 600; color: #6B7280;">{{ __('Fecha de Nacimiento') }}</small>
                                <strong class="text-dark" style="font-size: 1.05rem;">
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
                            <div class="icon-box me-3" style="width: 55px; height: 55px; background: #10B981; border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);">
                                <i class="fas fa-phone text-white fa-lg"></i>
                            </div>
                            <div class="flex-grow-1">
                                <small class="text-muted d-block mb-1" style="font-weight: 600; color: #6B7280;">{{ __('Teléfono') }}</small>
                                <strong class="text-dark" style="font-size: 1.05rem;">{{ $user->telefono ?? __('No especificado') }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="d-flex align-items-center mb-2">
                            <div class="icon-box me-3" style="width: 55px; height: 55px; background: #25D366; border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(37, 211, 102, 0.3);">
                                <i class="fab fa-whatsapp text-white fa-lg"></i>
                            </div>
                            <div class="flex-grow-1">
                                <small class="text-muted d-block mb-1" style="font-weight: 600; color: #6B7280;">{{ __('WhatsApp') }}</small>
                                <strong class="text-dark" style="font-size: 1.05rem;">{{ $user->whatsapp ?? __('No especificado') }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información de Ubicación -->
        @if($user->cliente)
        <div class="col-lg-6 mb-4">
            <div class="card shadow-lg border-0 h-100" style="border-radius: 20px; overflow: hidden; border: 2px solid #E5E7EB;">
                <div class="card-header text-white" style="background: #1E40AF; border: none;">
                    <h4 class="mb-0" style="font-weight: 700;">
                        <i class="fas fa-map-marker-alt"></i> {{ __('Información de Ubicación') }}
                    </h4>
                </div>
                <div class="card-body p-4" style="background: #F9FAFB;">
                    <div class="info-item mb-4">
                        <div class="mb-3">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-box me-3" style="width: 55px; height: 55px; background: #1E40AF; border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(30, 64, 175, 0.3);">
                                    <i class="fas fa-map-pin text-white fa-lg"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <small class="text-muted d-block mb-1" style="font-weight: 600; color: #6B7280;">{{ __('Dirección Completa') }}</small>
                                </div>
                            </div>
                            <div class="ps-5">
                                <strong class="text-dark d-block mb-3" style="font-size: 1.05rem; line-height: 1.6;">{{ $user->cliente->direccion ?? __('No especificada') }}</strong>
                                @if($user->cliente->nombre_conjunto_cerrado)
                                    <span class="badge mb-2 me-2 px-3 py-2" style="background: #DBEAFE; color: #1E40AF; border: 1px solid #93C5FD; font-weight: 600; font-size: 0.9rem;">
                                        <i class="fas fa-building"></i> {{ $user->cliente->nombre_conjunto_cerrado }}
                                    </span>
                                @endif
                                @if($user->cliente->interior_apartamento)
                                    <span class="badge mb-2 px-3 py-2" style="background: #FEF3C7; color: #92400E; border: 1px solid #FCD34D; font-weight: 600; font-size: 0.9rem;">
                                        <i class="fas fa-door-open"></i> {{ $user->cliente->interior_apartamento }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="info-item mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <div class="icon-box me-3" style="width: 55px; height: 55px; background: #3B82F6; border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);">
                                <i class="fas fa-city text-white fa-lg"></i>
                            </div>
                            <div class="flex-grow-1">
                                <small class="text-muted d-block mb-1" style="font-weight: 600; color: #6B7280;">{{ __('Ciudad') }}</small>
                                <strong class="text-dark" style="font-size: 1.05rem;">{{ $user->cliente->ciudad->municipio ?? __('No especificada') }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="d-flex align-items-center mb-2">
                            <div class="icon-box me-3" style="width: 55px; height: 55px; background: #1E40AF; border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(30, 64, 175, 0.3);">
                                <i class="fas fa-map text-white fa-lg"></i>
                            </div>
                            <div class="flex-grow-1">
                                <small class="text-muted d-block mb-1" style="font-weight: 600; color: #6B7280;">{{ __('Barrio') }}</small>
                                <strong class="text-dark" style="font-size: 1.05rem;">{{ $user->cliente->barrio->nombre ?? __('No especificado') }}</strong>
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
            <a href="{{ route('cliente.dashboard') }}" class="btn btn-lg px-5 shadow-sm" style="background: #1E40AF; color: white; font-weight: 600; border: none;">
                <i class="fas fa-arrow-left"></i> {{ __('Volver al Dashboard') }}
            </a>
        </div>
    </div>
</div>

<style>
    body {
        background: #F3F4F6;
    }
    
    .info-item {
        transition: all 0.3s ease;
        padding: 8px;
        border-radius: 10px;
    }
    
    .info-item:hover {
        transform: translateX(5px);
        background: rgba(30, 64, 175, 0.03);
    }
    
    .icon-box {
        transition: all 0.3s ease;
    }
    
    .icon-box:hover {
        transform: scale(1.1) rotate(5deg);
    }
    
    .card {
        transition: all 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important;
    }
    
    .badge {
        transition: all 0.2s ease;
    }
    
    .badge:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
</style>
@endsection

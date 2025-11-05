@extends('layouts.app')

@section('template_title')
    {{ $user->name ?? __('Mi Perfil') }}
@endsection

@section('content')
<div class="container-fluid py-4">
    <!-- Header con gradiente -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-lg border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 20px;">
                <div class="card-body p-4">
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
                                 style="width: 120px; height: 120px; object-fit: cover; border: 5px solid white;">
                        </div>
                        <div class="col">
                            <h2 class="text-white mb-1" style="font-weight: 700; text-shadow: 2px 2px 4px rgba(0,0,0,0.2);">
                                <i class="fas fa-user-circle"></i> {{ $user->name }}
                            </h2>
                            <p class="text-white-50 mb-2">
                                <i class="fas fa-envelope"></i> {{ $user->email }}
                            </p>
                            <div class="d-flex gap-2 flex-wrap">
                                <span class="badge badge-light px-3 py-2" style="font-size: 0.9rem;">
                                    <i class="fas fa-{{ $user->activo ? 'check-circle text-success' : 'times-circle text-danger' }}"></i> 
                                    {{ $user->activo ? __('Activo') : __('Inactivo') }}
                                </span>
                                @if($user->cliente)
                                    <span class="badge badge-light px-3 py-2" style="font-size: 0.9rem;">
                                        <i class="fas fa-paw text-primary"></i> {{ __('Cliente') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('cliente.perfil.edit', $user->id) }}" class="btn btn-light btn-lg shadow-sm">
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
                <div class="card-header text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                    <h4 class="mb-0">
                        <i class="fas fa-user-circle"></i> {{ __('Información Personal') }}
                    </h4>
                </div>
                <div class="card-body p-4">
                    <div class="info-item mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <div class="icon-box me-3" style="width: 50px; height: 50px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
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
                            <div class="icon-box me-3" style="width: 50px; height: 50px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
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
                            <div class="icon-box me-3" style="width: 50px; height: 50px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
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
                            <div class="icon-box me-3" style="width: 50px; height: 50px; background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
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
                            <div class="icon-box me-3" style="width: 50px; height: 50px; background: linear-gradient(135deg, #30cfd0 0%, #330867 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
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
                <div class="card-header text-white" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border: none;">
                    <h4 class="mb-0">
                        <i class="fas fa-map-marker-alt"></i> {{ __('Información de Ubicación') }}
                    </h4>
                </div>
                <div class="card-body p-4">
                    <div class="info-item mb-4">
                        <div class="mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <div class="icon-box me-3" style="width: 50px; height: 50px; background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-map-pin text-white fa-lg"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <small class="text-muted d-block">{{ __('Dirección Completa') }}</small>
                                </div>
                            </div>
                            <div class="ps-5">
                                <strong class="text-dark d-block mb-2">{{ $user->cliente->direccion ?? __('No especificada') }}</strong>
                                @if($user->cliente->nombre_conjunto_cerrado)
                                    <span class="badge badge-info mb-1">
                                        <i class="fas fa-building"></i> {{ $user->cliente->nombre_conjunto_cerrado }}
                                    </span>
                                @endif
                                @if($user->cliente->interior_apartamento)
                                    <span class="badge badge-secondary mb-1">
                                        <i class="fas fa-door-open"></i> {{ $user->cliente->interior_apartamento }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="info-item mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <div class="icon-box me-3" style="width: 50px; height: 50px; background: linear-gradient(135deg, #0ba360 0%, #3cba92 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-city text-white fa-lg"></i>
                            </div>
                            <div class="flex-grow-1">
                                <small class="text-muted d-block">{{ __('Ciudad') }}</small>
                                <strong class="text-dark">{{ $user->cliente->ciudad->municipio ?? __('No especificada') }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="d-flex align-items-center mb-2">
                            <div class="icon-box me-3" style="width: 50px; height: 50px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-map text-white fa-lg"></i>
                            </div>
                            <div class="flex-grow-1">
                                <small class="text-muted d-block">{{ __('Barrio') }}</small>
                                <strong class="text-dark">{{ $user->cliente->barrio->nombre ?? __('No especificado') }}</strong>
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
            <a href="{{ route('cliente.dashboard') }}" class="btn btn-secondary btn-lg px-5">
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
</style>
@endsection

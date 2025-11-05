@extends('layouts.app')

@section('template_title')
    {{ $user->name ?? __('Perfil de Usuario') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <!-- Tarjeta de Perfil -->
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <div class="card shadow-lg" style="border: 2px solid #000; border-radius: 15px;">
                    <div class="card-header bg-dark text-white">
                        <h3 class="card-title">{{ __('Perfil de Usuario') }}</h3>
                    </div>
                    <div class="card-body text-center">
                        <!-- Foto de Perfil -->
                        @php
                            $avatarUrl = asset('public/storage/img/default.png');
                            if ($user->avatar) {
                                // La imagen se guarda en public/storage/img/avatar/filename.png
                                // La ruta en BD es: storage/img/avatar/filename.png
                                if (strpos($user->avatar, 'storage/img/avatar/') === 0) {
                                    // Verificar si el archivo existe físicamente
                                    $filePath = public_path($user->avatar);
                                    if (file_exists($filePath)) {
                                        // Ruta nueva: public/storage/img/avatar/filename.png
                                        $avatarUrl = asset('public/' . $user->avatar);
                                    } else {
                                        // Intentar con solo el nombre del archivo
                                        $fileName = basename($user->avatar);
                                        $altPath = public_path('storage/img/avatar/' . $fileName);
                                        if (file_exists($altPath)) {
                                            $avatarUrl = asset('public/storage/img/avatar/' . $fileName);
                                        }
                                    }
                                } elseif (file_exists(public_path('storage/' . $user->avatar))) {
                                    // Ruta antigua en storage
                                    $avatarUrl = asset('storage/' . $user->avatar);
                                } elseif (file_exists(public_path($user->avatar))) {
                                    // Ruta absoluta
                                    $avatarUrl = asset('public/' . $user->avatar);
                                } elseif (\Illuminate\Support\Facades\Storage::disk('public')->exists($user->avatar)) {
                                    // Ruta en storage disk
                                    $avatarUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($user->avatar);
                                }
                            }
                        @endphp
                        <img class="profile-user-img img-fluid img-circle"
                             style="border: 5px solid #007bff; width: 150px; height: 150px; object-fit: cover;" 
                             src="{{ $avatarUrl }}"
                             alt="Foto de Perfil">

                        <h3 class="profile-username mt-3">{{ $user->name }}</h3>
                        <p class="text-muted">{{ __('Usuario Activo') }}</p>

                        <!-- Reemplazo de la lista por filas -->
                        <div class="row mb-2" style="font-size: 0.9rem;">
                            <div class="col-5 text-left">
                                <b>{{ __('Correo Electrónico:') }}</b>
                            </div>
                            <div class="col-7 text-right text-muted">
                                {{ $user->email }}
                            </div>
                        </div>
                        <div class="row mb-2" style="font-size: 0.9rem;">
                            <div class="col-6 text-left">
                                <b>{{ __('Teléfono:') }}</b>
                            </div>
                            <div class="col-6 text-right text-muted">
                                {{ $user->telefono }}
                            </div>
                        </div>
                        <div class="row mb-2" style="font-size: 0.9rem;">
                            <div class="col-6 text-left">
                                <b>{{ __('WhatsApp:') }}</b>
                            </div>
                            <div class="col-6 text-right text-muted">
                                {{ $user->whatsapp }}
                            </div>
                        </div>

                        <a href="{{ route('users.index') }}" class="btn btn-secondary btn-block"><b>{{ __('Volver') }}</b></a>
                    </div>
                </div>
            </div>

            <!-- Tarjeta de Información Detallada -->
            <div class="col-lg-8 col-md-6 col-sm-12 mb-4">
                <!-- Información Personal -->
                <div class="card shadow-lg mb-3" style="border: 2px solid #007bff; border-radius: 15px;">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0"><i class="fas fa-user"></i> {{ __('Información Personal') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <strong><i class="fas fa-id-card text-primary"></i> {{ __('Tipo de Documento:') }}</strong>
                                    <p class="text-muted mt-1">{{ $user->tipoDocumento->nombre ?? $user->tipo_documento ?? __('No especificado') }}</p>
                                </div>
                                <div class="mb-3">
                                    <strong><i class="fas fa-id-badge text-primary"></i> {{ __('Cédula:') }}</strong>
                                    <p class="text-muted mt-1">{{ $user->cedula ?? __('No especificada') }}</p>
                                </div>
                                <div class="mb-3">
                                    <strong><i class="fas fa-birthday-cake text-primary"></i> {{ __('Fecha de Nacimiento:') }}</strong>
                                    <p class="text-muted mt-1">
                                        @if($user->fecha_nacimiento)
                                            {{ $user->fecha_nacimiento instanceof \Carbon\Carbon ? $user->fecha_nacimiento->format('d/m/Y') : $user->fecha_nacimiento }}
                                        @else
                                            {{ __('No especificada') }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <strong><i class="fas fa-check-circle text-{{ $user->activo ? 'success' : 'danger' }}"></i> {{ __('Estado:') }}</strong>
                                    <p class="mt-1">
                                        <span class="badge badge-{{ $user->activo ? 'success' : 'danger' }}">
                                            {{ $user->activo ? __('Activo') : __('Inactivo') }}
                                        </span>
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <strong><i class="fas fa-calendar-plus text-primary"></i> {{ __('Creado el:') }}</strong>
                                    <p class="text-muted mt-1">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                                <div class="mb-3">
                                    <strong><i class="fas fa-calendar-check text-primary"></i> {{ __('Última Actualización:') }}</strong>
                                    <p class="text-muted mt-1">{{ $user->updated_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información de Ubicación -->
                @if($user->cliente)
                <div class="card shadow-lg" style="border: 2px solid #28a745; border-radius: 15px;">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0"><i class="fas fa-map-marker-alt"></i> {{ __('Información de Ubicación') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <strong><i class="fas fa-map-pin text-success"></i> {{ __('Dirección:') }}</strong>
                                <p class="text-muted mt-1">
                                    {{ $user->cliente->direccion ?? __('No especificada') }}
                                    @if($user->cliente->nombre_conjunto_cerrado)
                                        <br><small class="text-info">{{ __('Conjunto/Cerrado:') }} {{ $user->cliente->nombre_conjunto_cerrado }}</small>
                                    @endif
                                    @if($user->cliente->interior_apartamento)
                                        <br><small class="text-info">{{ __('Interior/Apto:') }} {{ $user->cliente->interior_apartamento }}</small>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong><i class="fas fa-city text-success"></i> {{ __('Ciudad:') }}</strong>
                                <p class="text-muted mt-1">{{ $user->cliente->ciudad->municipio ?? __('No especificada') }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong><i class="fas fa-map text-success"></i> {{ __('Barrio:') }}</strong>
                                <p class="text-muted mt-1">{{ $user->cliente->barrio->nombre ?? __('No especificado') }}</p>
                            </div>
                            @if($user->cliente->latitud && $user->cliente->longitud)
                            <div class="col-md-12 mb-3">
                                <strong><i class="fas fa-globe text-success"></i> {{ __('Coordenadas:') }}</strong>
                                <p class="text-muted mt-1">
                                    <small>Lat: {{ $user->cliente->latitud }}, Long: {{ $user->cliente->longitud }}</small>
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>
@endsection

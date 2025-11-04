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
                <div class="card shadow-lg" style="border: 2px solid #000; border-radius: 15px;">
                    <div class="card-header bg-dark text-white">
                        <h5 class="card-title mb-0">{{ __('Información del Usuario') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <strong>{{ __('Tipo de Documento:') }}</strong>
                                    <p class="text-muted">{{ $user->tipo_documento }}</p>
                                </div>
                                <div class="form-group">
                                    <strong>{{ __('Cédula:') }}</strong>
                                    <p class="text-muted">{{ $user->cedula }}</p>
                                </div>
                                <div class="form-group">
                                    <strong>{{ __('Fecha de Nacimiento:') }}</strong>
                                    <p class="text-muted">{{ $user->fecha_nacimiento }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <strong>{{ __('Activo:') }}</strong>
                                    <p class="text-muted">{{ $user->activo ? __('Sí') : __('No') }}</p>
                                </div>
                                <div class="form-group">
                                    <strong>{{ __('Creado el:') }}</strong>
                                    <p class="text-muted">{{ $user->created_at->format('d/m/Y') }}</p>
                                </div>
                                <div class="form-group">
                                    <strong>{{ __('Última Actualización:') }}</strong>
                                    <p class="text-muted">{{ $user->updated_at->format('d/m/Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@extends('layouts.app')

@section('template_title')
    {{ $user->name ?? __('Perfil de Usuario') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-4">
                <!-- Tarjeta de Perfil -->
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <!-- Foto de Perfil -->
                            @if($user->avatar && file_exists(public_path($user->avatar)))
                                <img class="profile-user-img img-fluid img-circle"
                                     src="{{ asset($user->avatar) }}"
                                     alt="Foto de Perfil">
                            @else
                                <img class="profile-user-img img-fluid img-circle"
                                     src="{{ asset('default-avatar.png') }}" 
                                     alt="Foto de Perfil">
                            @endif
                        </div>

                        <h3 class="profile-username text-center">{{ $user->name }}</h3>
                        <p class="text-muted text-center">{{ __('Usuario Activo') }}</p>

                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>{{ __('Correo Electrónico') }}</b> <a class="float-right">{{ $user->email }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>{{ __('Teléfono') }}</b> <a class="float-right">{{ $user->telefono }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>{{ __('WhatsApp') }}</b> <a class="float-right">{{ $user->whatsapp }}</a>
                            </li>
                        </ul>

                        <!-- Bloque adicional para mostrar las imágenes de ejemplo -->
                        <div class="text-center mt-3">
                            <h5>{{ __('Imágenes de Ejemplo') }}</h5>
                            <img src="{{ asset('storage/img/logo.jpg') }}" alt="Logo de la empresa" class="brand-image img-circle elevation-3" style="width: 30px; height: 30px; object-fit: cover;">
                            <img src="{{ asset('avatars/34/34.png') }}" alt="Logo de la empresa" class="brand-image img-circle elevation-3" style="width: 30px; height: 30px; object-fit: cover;">
                            <img src="{{ asset($user->avatar) }}" alt="Logo de la empresa" class="brand-image img-circle elevation-3" style="width: 30px; height: 30px; object-fit: cover;">
                        </div>
                        <div class="text-center mt-3">
                            <h5>{{ __('Imágenes de Ejemplo') }}</h5>
                            <p>Ruta generada para logo.jpg: {{ asset('storage/img/logo.jpg') }}</p>
                            <p>Ruta generada para 34.png: {{ asset('avatars/34/34.png') }}</p>
                            <p>Ruta generada para avatar del usuario: {{ asset($user->avatar) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <!-- Información Detallada -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">{{ __('Información del Usuario') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <strong>{{ __('Tipo de Documento:') }}</strong>
                                    {{ $user->tipo_documento }}
                                </div>
                                <div class="form-group">
                                    <strong>{{ __('Cédula:') }}</strong>
                                    {{ $user->cedula }}
                                </div>
                                <div class="form-group">
                                    <strong>{{ __('Fecha de Nacimiento:') }}</strong>
                                    {{ $user->fecha_nacimiento }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <strong>{{ __('Activo:') }}</strong>
                                    {{ $user->activo ? __('Sí') : __('No') }}
                                </div>
                                <div class="form-group">
                                    <strong>{{ __('Creado el:') }}</strong>
                                    {{ $user->created_at->format('d/m/Y') }}
                                </div>
                                <div class="form-group">
                                    <strong>{{ __('Última Actualización:') }}</strong>
                                    {{ $user->updated_at->format('d/m/Y') }}
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <a href="{{ route('users.index') }}" class="btn btn-secondary">{{ __('Volver') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@extends('layouts.app')

@section('template_title')
    Detalles del Usuario
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">
                                {{ __('Detalles del Usuario') }}
                            </span>
                            <div class="float-right">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-arrow-left"></i> Volver
                                </a>
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <!-- Tarjeta de Perfil -->
                            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                                <div class="card shadow-lg" style="border: 2px solid #000; border-radius: 15px;">
                                    <div class="card-header bg-dark text-white">
                                        <h3 class="card-title">{{ __('Perfil de Usuario') }}</h3>
                                    </div>
                                    <div class="card-body text-center">
                                        <!-- Foto de Perfil -->
                                        @if($user->avatar && file_exists(public_path($user->avatar)))
                                            <img class="profile-user-img img-fluid img-circle"
                                                 style="border: 5px solid #007bff;"
                                                 src="{{ asset('public/' . $user->avatar) }}"
                                                 alt="Foto de Perfil">
                                        @else
                                            <img class="profile-user-img img-fluid img-circle"
                                                 style="border: 5px solid #007bff;"
                                                 src="{{ asset('default-avatar.png') }}"
                                                 alt="Foto de Perfil">
                                        @endif

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

                                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-block"><b>{{ __('Volver') }}</b></a>
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

                        <div class="row mt-4">
                            <div class="col-12">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">{{ __('Volver') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

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
                    <div class="card-body">
                        <!-- Foto de Perfil -->
                        @if($user->avatar && file_exists(public_path($user->avatar)))
                            <img class="profile-user-img img-fluid img-circle border border-primary"
                                 src="{{ asset('public/' . $user->avatar) }}"
                                 alt="Foto de Perfil">
                        @else
                            <img class="profile-user-img img-fluid img-circle border border-primary"
                                 src="{{ asset('default-avatar.png') }}" 
                                 alt="Foto de Perfil">
                        @endif

                        <h3 class="profile-username mt-3">{{ $user->name }}</h3>
                        <p class="text-muted">{{ __('Usuario Activo') }}</p>

                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item bg-dark text-white" style="border: 2px solid #000; border-radius: 15px;">
                                <b>{{ __('Correo Electrónico') }}</b> 
                                <a class="float-right text-muted">{{ $user->email }}</a>
                            </li>
                            <li class="list-group-item bg-dark text-white" style="border: 2px solid #000; border-radius: 15px;">
                                <b>{{ __('Teléfono') }}</b> 
                                <a class="float-right text-muted">{{ $user->telefono }}</a>
                            </li>
                            <li class="list-group-item bg-dark text-white" style="border: 2px solid #000; border-radius: 15px;">
                                <b>{{ __('WhatsApp') }}</b> 
                                <a class="float-right text-muted">{{ $user->whatsapp }}</a>
                            </li>
                        </ul>
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

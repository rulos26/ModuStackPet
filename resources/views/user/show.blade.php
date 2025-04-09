@extends('layouts.app')

@section('template_title')
    {{ $user->name ?? __('Perfil de Usuario') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-4">
                <!-- Tarjeta de Perfil -->
                <div class="card shadow-lg" style="border: 2px solid #000; border-radius: 15px;">
                    <div class="card-header bg-dark text-white">
                        <h3 class="card-title text-warning">{{ __('Perfil de Usuario') }}</h3>
                    </div>
                    <div class="card-body text-center">
                        <!-- Foto de Perfil -->
                        @if($user->avatar && file_exists(public_path($user->avatar)))
                            <img class="profile-user-img img-fluid img-circle border border-warning"
                                 src="{{ asset('public/' . $user->avatar) }}"
                                 alt="Foto de Perfil">
                        @else
                            <img class="profile-user-img img-fluid img-circle border border-warning"
                                 src="{{ asset('default-avatar.png') }}" 
                                 alt="Foto de Perfil">
                        @endif

                        <h3 class="profile-username text-center mt-3 text-warning">{{ $user->name }}</h3>
                        <p class="text-muted text-center">{{ __('Usuario Activo') }}</p>

                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item bg-dark text-white" style="border: 2px solid #000; border-radius: 15px;">
                                <b class="text-warning">{{ __('Correo Electrónico') }}</b> 
                                <a class="float-right text-muted">{{ $user->email }}</a>
                            </li>
                            <li class="list-group-item bg-dark text-white" style="border: 2px solid #000; border-radius: 15px;">
                                <b class="text-warning">{{ __('Teléfono') }}</b> 
                                <a class="float-right text-muted">{{ $user->telefono }}</a>
                            </li>
                            <li class="list-group-item bg-dark text-white" style="border: 2px solid #000; border-radius: 15px;">
                                <b class="text-warning">{{ __('WhatsApp') }}</b> 
                                <a class="float-right text-muted">{{ $user->whatsapp }}</a>
                            </li>
                        </ul>
                        <a href="{{ route('users.index') }}" class="btn btn-warning btn-block"><b>{{ __('Volver') }}</b></a>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <!-- Información Detallada -->
                <div class="card shadow-lg" style="border: 2px solid #000; border-radius: 15px;">
                    <div class="card-header bg-dark text-white">
                        <h5 class="card-title mb-0 text-warning">{{ __('Información del Usuario') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <strong class="text-warning">{{ __('Tipo de Documento:') }}</strong>
                                    <p class="text-muted">{{ $user->tipo_documento }}</p>
                                </div>
                                <div class="form-group">
                                    <strong class="text-warning">{{ __('Cédula:') }}</strong>
                                    <p class="text-muted">{{ $user->cedula }}</p>
                                </div>
                                <div class="form-group">
                                    <strong class="text-warning">{{ __('Fecha de Nacimiento:') }}</strong>
                                    <p class="text-muted">{{ $user->fecha_nacimiento }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <strong class="text-warning">{{ __('Activo:') }}</strong>
                                    <p class="text-muted">{{ $user->activo ? __('Sí') : __('No') }}</p>
                                </div>
                                <div class="form-group">
                                    <strong class="text-warning">{{ __('Creado el:') }}</strong>
                                    <p class="text-muted">{{ $user->created_at->format('d/m/Y') }}</p>
                                </div>
                                <div class="form-group">
                                    <strong class="text-warning">{{ __('Última Actualización:') }}</strong>
                                    <p class="text-muted">{{ $user->updated_at->format('d/m/Y') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <a href="{{ route('users.index') }}" class="btn btn-warning btn-block"><b>{{ __('Volver') }}</b></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

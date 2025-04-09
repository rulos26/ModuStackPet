@extends('layouts.app')

@section('template_title')
    {{ $user->name ?? __('Perfil de Usuario') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-4">
                <!-- Tarjeta de Perfil -->
                <div class="card bg-dark text-white border border-danger">
                    <div class="card-header bg-danger">
                        <h3 class="card-title">{{ __('Perfil de Usuario') }}</h3>
                    </div>
                    <div class="card-body text-center">
                        <!-- Foto de Perfil -->
                        @if($user->avatar && file_exists(public_path($user->avatar)))
                            <img class="profile-user-img img-fluid img-circle border border-danger"
                                 src="{{ asset('public/' . $user->avatar) }}"
                                 alt="Foto de Perfil">
                        @else
                            <img class="profile-user-img img-fluid img-circle border border-danger"
                                 src="{{ asset('default-avatar.png') }}" 
                                 alt="Foto de Perfil">
                        @endif

                        <h3 class="profile-username text-center mt-3">{{ $user->name }}</h3>
                        <p class="text-danger text-center">{{ __('Usuario Activo') }}</p>

                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item bg-dark text-white border border-danger">
                                <b>{{ __('Correo Electrónico') }}</b> <a class="float-right text-danger">{{ $user->email }}</a>
                            </li>
                            <li class="list-group-item bg-dark text-white border border-danger">
                                <b>{{ __('Teléfono') }}</b> <a class="float-right text-danger">{{ $user->telefono }}</a>
                            </li>
                            <li class="list-group-item bg-dark text-white border border-danger">
                                <b>{{ __('WhatsApp') }}</b> <a class="float-right text-danger">{{ $user->whatsapp }}</a>
                            </li>
                        </ul>
                        <a href="{{ route('users.index') }}" class="btn btn-danger btn-block"><b>{{ __('Volver') }}</b></a>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <!-- Información Detallada -->
                <div class="card bg-dark text-white border border-danger">
                    <div class="card-header bg-danger">
                        <h5 class="card-title mb-0">{{ __('Información del Usuario') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <strong class="text-danger">{{ __('Tipo de Documento:') }}</strong>
                                    {{ $user->tipo_documento }}
                                </div>
                                <div class="form-group">
                                    <strong class="text-danger">{{ __('Cédula:') }}</strong>
                                    {{ $user->cedula }}
                                </div>
                                <div class="form-group">
                                    <strong class="text-danger">{{ __('Fecha de Nacimiento:') }}</strong>
                                    {{ $user->fecha_nacimiento }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <strong class="text-danger">{{ __('Activo:') }}</strong>
                                    {{ $user->activo ? __('Sí') : __('No') }}
                                </div>
                                <div class="form-group">
                                    <strong class="text-danger">{{ __('Creado el:') }}</strong>
                                    {{ $user->created_at->format('d/m/Y') }}
                                </div>
                                <div class="form-group">
                                    <strong class="text-danger">{{ __('Última Actualización:') }}</strong>
                                    {{ $user->updated_at->format('d/m/Y') }}
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <a href="{{ route('users.index') }}" class="btn btn-danger btn-block"><b>{{ __('Volver') }}</b></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@extends('layouts.app')

@section('template_title')
    {{ __('Detalles de la Mascota') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-widget widget-user">
                    <div class="widget-user-header bg-primary">
                        <h3 class="widget-user-username">{{ $mascota->nombre }}</h3>
                        <h5 class="widget-user-desc">{{ $mascota->raza->nombre ?? 'No especificada' }}</h5>
                    </div>
                    <div class="widget-user-image">
                        @if($mascota->avatar)
                            <img class="img-circle elevation-2" src="{{ asset('public/' . $mascota->avatar) }}" alt="Avatar de {{ $mascota->nombre }}">
                        @else
                            <img class="img-circle elevation-2" src="{{ asset('public/default-avatar.png') }}" alt="Avatar por defecto">
                        @endif
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">{{ __('Edad') }}</h5>
                                    <span class="description-text">{{ $mascota->edad }}</span>
                                </div>
                            </div>
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">{{ __('Género') }}</h5>
                                    <span class="description-text">{{ $mascota->genero }}</span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="description-block">
                                    <h5 class="description-header">{{ __('Barrio') }}</h5>
                                    <span class="description-text">{{ $mascota->barrio->nombre ?? 'No especificado' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <strong>{{ __('Fecha de Nacimiento:') }}</strong>
                                    {{ $mascota->fecha_nacimiento ? \Carbon\Carbon::parse($mascota->fecha_nacimiento)->format('d/m/Y') : 'No especificada' }}
                                </div>
                                <div class="form-group">
                                    <strong>{{ __('Vacunas Completas:') }}</strong>
                                    @if($mascota->vacunas_completas)
                                        <span class="badge badge-success">{{ __('Sí') }}</span>
                                    @else
                                        <span class="badge badge-danger">{{ __('No') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <strong>{{ __('Última Vacunación:') }}</strong>
                                    {{ $mascota->ultima_vacunacion ? \Carbon\Carbon::parse($mascota->ultima_vacunacion)->format('d/m/Y') : 'No especificada' }}
                                </div>
                                <div class="form-group">
                                    <strong>{{ __('Raza:') }}</strong>
                                    {{ $mascota->raza->nombre ?? 'No especificada' }}
                                </div>
                                <div class="form-group">
                                    <strong>{{ __('Interior/Apartamento:') }}</strong>
                                    {{ $mascota->interior_apto ?? 'No especificado' }}
                                </div>
                                <div class="form-group">
                                    <strong>{{ __('Recomendaciones:') }}</strong>
                                    {{ $mascota->recomendaciones ?? 'No especificadas' }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <strong>{{ __('Esterilizado:') }}</strong>
                                    @if($mascota->esterilizado)
                                        <span class="badge badge-success">{{ __('Sí') }}</span>
                                    @else
                                        <span class="badge badge-danger">{{ __('No') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <strong>{{ __('Dirección:') }}</strong>
                                    {{ $mascota->direccion ?? 'No especificada' }}
                                </div>
                                <div class="form-group">
                                    <strong>{{ __('Comportamiento:') }}</strong>
                                    {{ $mascota->comportamiento ?? 'No especificado' }}
                                </div>
                                <div class="form-group">
                                    <strong>{{ __('Enfermedades:') }}</strong>
                                    {{ $mascota->enfermedades ?? 'No especificadas' }}
                                </div>
                                <div class="form-group">
                                    <strong>{{ __('Último Examen Médico:') }}</strong>
                                    {{ $mascota->ultimo_examen_medico ? \Carbon\Carbon::parse($mascota->ultimo_examen_medico)->format('d/m/Y') : 'No especificado' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

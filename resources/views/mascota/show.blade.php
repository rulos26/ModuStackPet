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
                    <div class="card-header bg-primary">
                        <h3 class="card-title">{{ __('Información Detallada de la Mascota') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <strong>{{ __('Fecha de Nacimiento:') }}</strong>
                                        <span class="float-right">{{ $mascota->fecha_nacimiento ? \Carbon\Carbon::parse($mascota->fecha_nacimiento)->format('d/m/Y') : 'No especificada' }}</span>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>{{ __('Vacunas Completas:') }}</strong>
                                        <span class="float-right">
                                            @if($mascota->vacunas_completas)
                                                <span class="badge badge-success">{{ __('Sí') }}</span>
                                            @else
                                                <span class="badge badge-danger">{{ __('No') }}</span>
                                            @endif
                                        </span>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>{{ __('Última Vacunación:') }}</strong>
                                        <span class="float-right">{{ $mascota->ultima_vacunacion ? \Carbon\Carbon::parse($mascota->ultima_vacunacion)->format('d/m/Y') : 'No especificada' }}</span>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>{{ __('Raza:') }}</strong>
                                        <span class="float-right">{{ $mascota->raza->nombre ?? 'No especificada' }}</span>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>{{ __('Interior/Apartamento:') }}</strong>
                                        <span class="float-right">{{ $mascota->interior_apto ?? 'No especificado' }}</span>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>{{ __('Recomendaciones:') }}</strong>
                                        <span class="float-right">{{ $mascota->recomendaciones ?? 'No especificadas' }}</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <strong>{{ __('Esterilizado:') }}</strong>
                                        <span class="float-right">
                                            @if($mascota->esterilizado)
                                                <span class="badge badge-success">{{ __('Sí') }}</span>
                                            @else
                                                <span class="badge badge-danger">{{ __('No') }}</span>
                                            @endif
                                        </span>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>{{ __('Dirección:') }}</strong>
                                        <span class="float-right">{{ $mascota->direccion ?? 'No especificada' }}</span>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>{{ __('Comportamiento:') }}</strong>
                                        <span class="float-right">{{ $mascota->comportamiento ?? 'No especificado' }}</span>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>{{ __('Enfermedades:') }}</strong>
                                        <span class="float-right">{{ $mascota->enfermedades ?? 'No especificadas' }}</span>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>{{ __('Último Examen Médico:') }}</strong>
                                        <span class="float-right">{{ $mascota->ultimo_examen_medico ? \Carbon\Carbon::parse($mascota->ultimo_examen_medico)->format('d/m/Y') : 'No especificado' }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@extends('layouts.app')

@section('template_title')
    {{ __('Detalles de la Mascota') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Detalles de la Mascota') }}</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('mascotas.index') }}"> {{ __('Volver') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <strong>{{ __('Avatar:') }}</strong>
                                    @if($mascota->avatar)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $mascota->avatar) }}" alt="Avatar de {{ $mascota->nombre }}" class="img-thumbnail" style="max-width: 150px;">
                                        </div>
                                    @else
                                        <p>{{ __('No hay imagen disponible') }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <strong>{{ __('Nombre:') }}</strong>
                                    {{ $mascota->nombre }}
                                </div>
                                <div class="form-group">
                                    <strong>{{ __('Edad:') }}</strong>
                                    {{ $mascota->edad }}
                                </div>
                                <div class="form-group">
                                    <strong>{{ __('Género:') }}</strong>
                                    {{ $mascota->genero }}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <strong>{{ __('Fecha de Nacimiento:') }}</strong>
                                    {{ $mascota->fecha_nacimiento ? \Carbon\Carbon::parse($mascota->fecha_nacimiento)->format('d/m/Y') : 'No especificada' }}
                                </div>
                                <div class="form-group">
                                    <strong>{{ __('Raza:') }}</strong>
                                    {{ $mascota->raza->nombre ?? 'No especificada' }} ({{ $mascota->raza->tipo_mascota ?? 'No especificada' }})
                                </div>
                                <div class="form-group">
                                    <strong>{{ __('Barrio:') }}</strong>
                                    {{ $mascota->barrio->nombre ?? 'No especificado' }} ({{ $mascota->barrio->localidad ?? 'No especificada' }})
                                </div>
                            </div>
                            <div class="col-md-6">
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
                                    <strong>{{ __('Esterilizado:') }}</strong>
                                    @if($mascota->esterilizado)
                                        <span class="badge badge-success">{{ __('Sí') }}</span>
                                    @else
                                        <span class="badge badge-danger">{{ __('No') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <strong>{{ __('Dirección:') }}</strong>
                                    {{ $mascota->direccion ?? 'No especificada' }}
                                </div>
                                <div class="form-group">
                                    <strong>{{ __('Interior/Apartamento:') }}</strong>
                                    {{ $mascota->interior_apto ?? 'No especificado' }}
                                </div>
                                <div class="form-group">
                                    <strong>{{ __('Comportamiento:') }}</strong>
                                    {{ $mascota->comportamiento ?? 'No especificado' }}
                                </div>
                                <div class="form-group">
                                    <strong>{{ __('Recomendaciones:') }}</strong>
                                    {{ $mascota->recomendaciones ?? 'No especificadas' }}
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

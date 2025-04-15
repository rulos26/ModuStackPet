@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Tarjeta principal -->
            <div class="card">
                <!-- Encabezado de la tarjeta -->
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">{{ __('Detalles de Vacunas y Certificaciones') }}</h3>
                        <div class="btn-group">
                            <a href="{{ route('vacunas-certificaciones.edit', $vacunaCertificacion) }}" class="btn btn-light" title="{{ __('Editar') }}">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="{{ route('vacunas-certificaciones.index') }}" class="btn btn-light" title="{{ __('Volver') }}">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Cuerpo de la tarjeta -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <strong>{{ __('Mascota:') }}</strong>
                                    <span class="float-right">{{ $vacunaCertificacion->mascota->nombre }}</span>
                                </li>
                                <li class="list-group-item">
                                    <strong>{{ __('Fecha Última Vacuna:') }}</strong>
                                    <span class="float-right">{{ $vacunaCertificacion->fecha_ultima_vacuna_formateada ?? 'No especificada' }}</span>
                                </li>
                                <li class="list-group-item">
                                    <strong>{{ __('Operaciones:') }}</strong>
                                    <span class="float-right">{{ $vacunaCertificacion->operaciones ?? 'No especificadas' }}</span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <strong>{{ __('Certificado Veterinario:') }}</strong>
                                    <span class="float-right">
                                        @if($vacunaCertificacion->certificado_veterinario)
                                            <a href="{{ $vacunaCertificacion->certificado_veterinario_url }}" target="_blank" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> {{ __('Ver') }}
                                            </a>
                                        @else
                                            {{ __('No disponible') }}
                                        @endif
                                    </span>
                                </li>
                                <li class="list-group-item">
                                    <strong>{{ __('Cédula Propietario:') }}</strong>
                                    <span class="float-right">
                                        @if($vacunaCertificacion->cedula_propietario)
                                            <a href="{{ $vacunaCertificacion->cedula_propietario_url }}" target="_blank" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> {{ __('Ver') }}
                                            </a>
                                        @else
                                            {{ __('No disponible') }}
                                        @endif
                                    </span>
                                </li>
                                <li class="list-group-item">
                                    <strong>{{ __('Fecha de Creación:') }}</strong>
                                    <span class="float-right">{{ $vacunaCertificacion->created_at->format('d/m/Y H:i') }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

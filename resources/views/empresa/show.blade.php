@extends('layouts.app')

@section('template_title')
    {{ __('Información de la Empresa') }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="card-title mb-0">{{ __('Información de la Empresa') }}</h3>
                            <div class="card-tools">
                                <a href="{{ route('empresas.edit', $empresa->id) }}" class="btn btn-primary btn-sm me-2">
                                    <i class="fas fa-edit"></i> {{ __('Editar') }}
                                </a>
                                <a href="{{ route('empresas.pdf', $empresa->id) }}" class="btn btn-success btn-sm" target="_blank">
                                    <i class="fas fa-file-pdf"></i> {{ __('Descargar PDF') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Logo y Datos Básicos -->
                            <div class="col-md-4">
                                <div class="text-center mb-4">
                                    @if($empresa->logo)
                                        <img src="{{ $empresa->logo_url }}" alt="Logo" class="img-fluid rounded-circle" style="max-width: 200px; border: 3px solid #dee2e6;">
                                    @else
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 200px; height: 200px;">
                                            <i class="fas fa-building fa-4x text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="text-center mb-4">
                                    <h4 class="mb-1">{{ $empresa->nombre_legal }}</h4>
                                    <p class="text-muted mb-0">{{ $empresa->tipoEmpresa->nombre }}</p>
                                </div>
                            </div>

                            <!-- Información Detallada -->
                            <div class="col-md-8">
                                <div class="row">
                                    <!-- Datos de Identificación -->
                                    <div class="col-md-6">
                                        <div class="card mb-4">
                                            <div class="card-header bg-light">
                                                <h5 class="card-title mb-0">{{ __('Datos de Identificación') }}</h5>
                                            </div>
                                            <div class="card-body">
                                                <dl class="row mb-0">
                                                    <dt class="col-sm-4">{{ __('NIT') }}</dt>
                                                    <dd class="col-sm-8">{{ $empresa->nit }}</dd>

                                                    <dt class="col-sm-4">{{ __('Representante Legal') }}</dt>
                                                    <dd class="col-sm-8">{{ $empresa->representante_legal }}</dd>

                                                    <dt class="col-sm-4">{{ __('Sector') }}</dt>
                                                    <dd class="col-sm-8">{{ $empresa->sector->nombre }}</dd>

                                                    <dt class="col-sm-4">{{ __('Estado') }}</dt>
                                                    <dd class="col-sm-8">
                                                        <span class="badge {{ $empresa->estado ? 'bg-success' : 'bg-danger' }}">
                                                            {{ $empresa->estado ? __('Activa') : __('Inactiva') }}
                                                        </span>
                                                    </dd>
                                                </dl>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Datos de Contacto -->
                                    <div class="col-md-6">
                                        <div class="card mb-4">
                                            <div class="card-header bg-light">
                                                <h5 class="card-title mb-0">{{ __('Datos de Contacto') }}</h5>
                                            </div>
                                            <div class="card-body">
                                                <dl class="row mb-0">
                                                    <dt class="col-sm-4">{{ __('Teléfono') }}</dt>
                                                    <dd class="col-sm-8">{{ $empresa->telefono ?? __('No especificado') }}</dd>

                                                    <dt class="col-sm-4">{{ __('Email') }}</dt>
                                                    <dd class="col-sm-8">{{ $empresa->email ?? __('No especificado') }}</dd>
                                                </dl>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Ubicación -->
                                    <div class="col-12">
                                        <div class="card mb-4">
                                            <div class="card-header bg-light">
                                                <h5 class="card-title mb-0">{{ __('Ubicación') }}</h5>
                                            </div>
                                            <div class="card-body">
                                                <dl class="row mb-0">
                                                    <dt class="col-sm-2">{{ __('Departamento') }}</dt>
                                                    <dd class="col-sm-4">{{ $empresa->departamento->nombre }}</dd>

                                                    <dt class="col-sm-2">{{ __('Ciudad') }}</dt>
                                                    <dd class="col-sm-4">{{ $empresa->ciudad->municipio }}</dd>

                                                    <dt class="col-sm-2">{{ __('Dirección') }}</dt>
                                                    <dd class="col-sm-10">{{ $empresa->direccion ?? __('No especificada') }}</dd>
                                                </dl>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card {
            box-shadow: 0 0 1px rgba(0,0,0,.125), 0 0 2px rgba(0,0,0,.075);
            border: none;
        }
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid rgba(0,0,0,.125);
        }
        .card-title {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 600;
        }
        dt {
            font-weight: 600;
            color: #6c757d;
        }
        .badge {
            font-size: 0.875rem;
            padding: 0.35em 0.65em;
        }
    </style>
@endsection

@extends('layouts.app')

@section('template_title')
    {{ __('Detalles del Municipio') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Detalles del Municipio') }}</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('ciudades.index') }}">{{ __('Volver') }}</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <strong>{{ __('Municipio') }}:</strong>
                            {{ $ciudad->municipio }}
                        </div>
                        <div class="form-group">
                            <strong>{{ __('Departamento') }}:</strong>
                            {{ $ciudad->departamento->nombre }}
                        </div>
                        <div class="form-group">
                            <strong>{{ __('Estado') }}:</strong>
                            <span class="badge {{ $ciudad->estado == 1 ? 'bg-success' : 'bg-danger' }}">
                                {{ $ciudad->estado == 1 ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>
                        <div class="form-group">
                            <strong>{{ __('Fecha de Creación') }}:</strong>
                            {{ $ciudad->created_at->format('d/m/Y H:i') }}
                        </div>
                        <div class="form-group">
                            <strong>{{ __('Última Actualización') }}:</strong>
                            {{ $ciudad->updated_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


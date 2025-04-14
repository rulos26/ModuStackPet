@extends('layouts.app')

@section('template_title')
    Detalles del Departamento
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="card-title">
                                <i class="fas fa-eye"></i> Detalles del Departamento
                            </span>
                            <div>
                                @hasanyrole('Superadmin|Admin')
                                <a href="{{ route('departamentos.edit', $departamento->id_departamento) }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                @endhasanyrole
                                <a href="{{ route('departamentos.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-left"></i> Volver
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label class="form-label">Nombre del Departamento:</label>
                            <p class="form-control-static">{{ $departamento->nombre }}</p>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Estado:</label>
                            <p class="form-control-static">
                                <span class="badge {{ $departamento->estado == 1 ? 'bg-success' : 'bg-danger' }}">
                                    {{ $departamento->estado == 1 ? 'Activo' : 'Inactivo' }}
                                </span>
                            </p>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Fecha de Creación:</label>
                            <p class="form-control-static">{{ $departamento->created_at->format('d/m/Y H:i:s') }}</p>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Última Actualización:</label>
                            <p class="form-control-static">{{ $departamento->updated_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

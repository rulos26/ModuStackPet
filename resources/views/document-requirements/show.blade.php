@extends('layouts.app')

@section('template_title')
    Detalle del Requisito Documental
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span id="card_title">
                            <i class="fas fa-file-alt"></i> {{ $documentRequirement->nombre }}
                        </span>
                        <div class="float-right">
                            <a href="{{ route('admin.document-requirements.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Información del Requisito</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Código</th>
                                    <td><strong>{{ $documentRequirement->codigo }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Nombre</th>
                                    <td>{{ $documentRequirement->nombre }}</td>
                                </tr>
                                <tr>
                                    <th>Descripción</th>
                                    <td>{{ $documentRequirement->descripcion ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Obligatorio</th>
                                    <td>
                                        @if($documentRequirement->obligatorio)
                                            <span class="badge bg-success">Sí</span>
                                        @else
                                            <span class="badge bg-secondary">No</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Estado</th>
                                    <td>
                                        @if($documentRequirement->activo)
                                            <span class="badge bg-success">Activo</span>
                                        @else
                                            <span class="badge bg-danger">Inactivo</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Tipo de Validación</th>
                                    <td>{{ $documentRequirement->tipo_validacion ?? 'Ninguna' }}</td>
                                </tr>
                                <tr>
                                    <th>Días de Validez</th>
                                    <td>{{ $documentRequirement->dias_validez ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Formatos Permitidos</th>
                                    <td>{{ implode(', ', $documentRequirement->formatos_permitidos ?? []) }}</td>
                                </tr>
                                <tr>
                                    <th>Tamaño Máximo</th>
                                    <td>{{ $documentRequirement->tamaño_maximo_kb }} KB</td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h5>Historial de Cambios</h5>
                            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Usuario</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($documentRequirement->logs as $log)
                                        <tr>
                                            <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                                            <td>{{ $log->user->name ?? 'N/A' }}</td>
                                            <td><span class="badge bg-info">{{ ucfirst($log->accion) }}</span></td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center">No hay registros</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('admin.document-requirements.edit', $documentRequirement->id) }}" class="btn btn-success">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        @hasrole('Superadmin')
                        <form action="{{ route('admin.document-requirements.destroy', $documentRequirement->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('¿Está seguro?')">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </form>
                        @endhasrole
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


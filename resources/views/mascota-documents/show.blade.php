@extends('layouts.app')

@section('template_title')
    Ver Documento - {{ $mascotaDocument->documentRequirement->nombre }}
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span id="card_title">
                            <i class="fas fa-file-alt"></i> Detalle del Documento
                        </span>
                        <div class="float-right">
                            <a href="{{ route('mascota-documents.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Información del Documento</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Mascota</th>
                                    <td>{{ $mascotaDocument->mascota->nombre }}</td>
                                </tr>
                                <tr>
                                    <th>Tipo de Documento</th>
                                    <td>{{ $mascotaDocument->documentRequirement->nombre }}</td>
                                </tr>
                                <tr>
                                    <th>Archivo</th>
                                    <td>{{ $mascotaDocument->nombre_archivo }}</td>
                                </tr>
                                <tr>
                                    <th>Estado</th>
                                    <td>
                                        @if($mascotaDocument->estado === 'aprobado')
                                            <span class="badge bg-success">Aprobado</span>
                                        @elseif($mascotaDocument->estado === 'rechazado')
                                            <span class="badge bg-danger">Rechazado</span>
                                        @elseif($mascotaDocument->estado === 'pendiente_correccion')
                                            <span class="badge bg-warning">Pendiente Corrección</span>
                                        @else
                                            <span class="badge bg-secondary">Pendiente</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Fecha de Emisión</th>
                                    <td>{{ $mascotaDocument->fecha_emision ? $mascotaDocument->fecha_emision->format('d/m/Y') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Fecha de Vencimiento</th>
                                    <td>
                                        @if($mascotaDocument->fecha_vencimiento)
                                            @if($mascotaDocument->estaVencido())
                                                <span class="text-danger">{{ $mascotaDocument->fecha_vencimiento->format('d/m/Y') }} (Vencido)</span>
                                            @elseif($mascotaDocument->proximoAVencer())
                                                <span class="text-warning">{{ $mascotaDocument->fecha_vencimiento->format('d/m/Y') }} (Próximo a vencer)</span>
                                            @else
                                                {{ $mascotaDocument->fecha_vencimiento->format('d/m/Y') }}
                                            @endif
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Subido por</th>
                                    <td>{{ $mascotaDocument->usuarioSubio->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Fecha de Subida</th>
                                    <td>{{ $mascotaDocument->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h5>Acciones</h5>
                            <div class="d-grid gap-2">
                                <a href="{{ route('mascota-documents.descargar', $mascotaDocument->id) }}" class="btn btn-primary">
                                    <i class="fas fa-download"></i> Descargar Documento
                                </a>
                                
                                @hasanyrole('Superadmin|Admin')
                                @if($mascotaDocument->estado !== 'aprobado')
                                    <form action="{{ route('mascota-documents.aprobar', $mascotaDocument->id) }}" method="POST">
                                        @csrf
                                        <div class="mb-2">
                                            <label for="notas_aprobar">Notas (opcional)</label>
                                            <textarea name="notas" id="notas_aprobar" class="form-control" rows="2"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-success w-100">
                                            <i class="fas fa-check"></i> Aprobar Documento
                                        </button>
                                    </form>
                                @endif

                                @if($mascotaDocument->estado !== 'rechazado')
                                    <form action="{{ route('mascota-documents.rechazar', $mascotaDocument->id) }}" method="POST">
                                        @csrf
                                        <div class="mb-2">
                                            <label for="motivo_rechazo">Motivo de Rechazo *</label>
                                            <textarea name="motivo_rechazo" id="motivo_rechazo" class="form-control" rows="3" required></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-danger w-100">
                                            <i class="fas fa-times"></i> Rechazar Documento
                                        </button>
                                    </form>
                                @endif
                                @endhasanyrole

                                @if(auth()->user()->id === $mascotaDocument->mascota->user_id || auth()->user()->hasRole('Superadmin') || auth()->user()->hasRole('Admin'))
                                    <a href="{{ route('mascota-documents.edit', $mascotaDocument->id) }}" class="btn btn-warning">
                                        <i class="fas fa-edit"></i> Editar Documento
                                    </a>
                                @endif
                            </div>

                            @if($mascotaDocument->motivo_rechazo)
                                <div class="alert alert-danger mt-3">
                                    <strong>Motivo de Rechazo:</strong><br>
                                    {{ $mascotaDocument->motivo_rechazo }}
                                </div>
                            @endif

                            @if($mascotaDocument->notas)
                                <div class="alert alert-info mt-3">
                                    <strong>Notas:</strong><br>
                                    {{ $mascotaDocument->notas }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


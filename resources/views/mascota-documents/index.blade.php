@extends('layouts.app')

@section('template_title')
    Documentos de Mascotas
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span id="card_title">
                            <i class="fas fa-file-alt"></i> Documentos de Mascotas
                        </span>
                        <div class="float-right">
                            <a href="{{ route('mascotas.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Volver a Mascotas
                            </a>
                        </div>
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show m-4" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Mascota</th>
                                    <th>Tipo de Documento</th>
                                    <th>Archivo</th>
                                    <th>Estado</th>
                                    <th>Fecha Vencimiento</th>
                                    <th>Subido por</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($documents as $document)
                                <tr>
                                    <td>{{ $document->mascota->nombre }}</td>
                                    <td>{{ $document->documentRequirement->nombre }}</td>
                                    <td>{{ $document->nombre_archivo }}</td>
                                    <td>
                                        @if($document->estado === 'aprobado')
                                            <span class="badge bg-success">Aprobado</span>
                                        @elseif($document->estado === 'rechazado')
                                            <span class="badge bg-danger">Rechazado</span>
                                        @elseif($document->estado === 'pendiente_correccion')
                                            <span class="badge bg-warning">Pendiente Corrección</span>
                                        @else
                                            <span class="badge bg-secondary">Pendiente</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($document->fecha_vencimiento)
                                            @if($document->estaVencido())
                                                <span class="text-danger">{{ $document->fecha_vencimiento->format('d/m/Y') }}</span>
                                            @elseif($document->proximoAVencer())
                                                <span class="text-warning">{{ $document->fecha_vencimiento->format('d/m/Y') }}</span>
                                            @else
                                                {{ $document->fecha_vencimiento->format('d/m/Y') }}
                                            @endif
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>{{ $document->usuarioSubio->name ?? 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('mascota-documents.show', $document->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('mascota-documents.descargar', $document->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        @hasanyrole('Superadmin|Admin')
                                        @if($document->estado === 'pendiente' || $document->estado === 'pendiente_correccion')
                                            <form action="{{ route('mascota-documents.aprobar', $document->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                        @endhasanyrole
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No hay documentos registrados</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $documents->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


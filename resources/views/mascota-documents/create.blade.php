@extends('layouts.app')

@section('template_title')
    Subir Documentos de Ingreso - {{ $mascota->nombre }}
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span id="card_title">
                            <i class="fas fa-upload"></i> Subir Documentos de Ingreso
                        </span>
                        <div class="float-right">
                            <a href="{{ route('mascotas.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="alert alert-info">
                        <strong>Mascota:</strong> {{ $mascota->nombre }}<br>
                        <strong>Raza:</strong> {{ $mascota->raza->nombre ?? 'N/A' }}
                    </div>

                    <h5 class="mb-4">Documentos Requeridos</h5>

                    <form action="{{ route('mascota-documents.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="mascota_id" value="{{ $mascota->id }}">

                        @foreach($requirements as $requirement)
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    {{ $requirement->nombre }}
                                    @if($requirement->obligatorio)
                                        <span class="badge bg-danger">Obligatorio</span>
                                    @else
                                        <span class="badge bg-secondary">Opcional</span>
                                    @endif
                                </h6>
                                @if($requirement->descripcion)
                                    <small class="text-muted">{{ $requirement->descripcion }}</small>
                                @endif
                            </div>
                            <div class="card-body">
                                @php
                                    $documentoExistente = $documentosExistentes->get($requirement->id);
                                @endphp

                                @if($documentoExistente)
                                    <div class="alert alert-warning">
                                        <strong>Documento ya subido:</strong> {{ $documentoExistente->nombre_archivo }}<br>
                                        <strong>Estado:</strong> 
                                        <span class="badge bg-{{ $documentoExistente->estado === 'aprobado' ? 'success' : ($documentoExistente->estado === 'rechazado' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($documentoExistente->estado) }}
                                        </span>
                                        @if($documentoExistente->fecha_vencimiento)
                                            <br><strong>Vence:</strong> {{ $documentoExistente->fecha_vencimiento->format('d/m/Y') }}
                                        @endif
                                        <br>
                                        <a href="{{ route('mascota-documents.show', $documentoExistente->id) }}" class="btn btn-sm btn-info mt-2">
                                            <i class="fas fa-eye"></i> Ver Documento
                                        </a>
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label for="archivo_{{ $requirement->id }}">
                                        Archivo 
                                        @if($requirement->obligatorio && !$documentoExistente)
                                            <span class="text-danger">*</span>
                                        @endif
                                    </label>
                                    <input type="file" 
                                           class="form-control @error('archivo_' . $requirement->id) is-invalid @enderror" 
                                           id="archivo_{{ $requirement->id }}"
                                           name="archivo_{{ $requirement->id }}"
                                           accept="{{ implode(',', array_map(function($f) { return '.' . $f; }, $requirement->formatos_permitidos ?? ['pdf', 'jpg', 'jpeg', 'png'])) }}"
                                           {{ $requirement->obligatorio && !$documentoExistente ? 'required' : '' }}>
                                    <small class="form-text text-muted">
                                        Formatos: {{ implode(', ', $requirement->formatos_permitidos ?? ['PDF', 'JPG', 'PNG']) }} | 
                                        Máximo: {{ $requirement->tamaño_maximo_kb ?? 2048 }} KB
                                    </small>
                                    @error('archivo_' . $requirement->id)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                @if($requirement->tipo_validacion === 'fecha_vencimiento')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="fecha_emision_{{ $requirement->id }}">Fecha de Emisión</label>
                                            <input type="date" 
                                                   class="form-control" 
                                                   id="fecha_emision_{{ $requirement->id }}"
                                                   name="fecha_emision_{{ $requirement->id }}"
                                                   max="{{ date('Y-m-d') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="fecha_vencimiento_{{ $requirement->id }}">Fecha de Vencimiento</label>
                                            <input type="date" 
                                                   class="form-control" 
                                                   id="fecha_vencimiento_{{ $requirement->id }}"
                                                   name="fecha_vencimiento_{{ $requirement->id }}"
                                                   min="{{ date('Y-m-d') }}">
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <input type="hidden" name="requirement_{{ $requirement->id }}" value="{{ $requirement->id }}">
                            </div>
                        </div>
                        @endforeach

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload"></i> Subir Documentos
                            </button>
                            <a href="{{ route('mascotas.index') }}" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


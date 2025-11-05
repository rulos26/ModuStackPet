@extends('layouts.app')

@section('template_title')
    Editar Documento - {{ $mascotaDocument->documentRequirement->nombre }}
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span id="card_title">
                            <i class="fas fa-edit"></i> Editar Documento
                        </span>
                        <div class="float-right">
                            <a href="{{ route('mascota-documents.show', $mascotaDocument->id) }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('mascota-documents.update', $mascotaDocument->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="alert alert-info">
                            <strong>Mascota:</strong> {{ $mascotaDocument->mascota->nombre }}<br>
                            <strong>Tipo de Documento:</strong> {{ $mascotaDocument->documentRequirement->nombre }}
                        </div>

                        <div class="form-group mb-3">
                            <label for="archivo">Nuevo Archivo (opcional)</label>
                            <input type="file" 
                                   class="form-control @error('archivo') is-invalid @enderror" 
                                   id="archivo"
                                   name="archivo"
                                   accept=".pdf,.jpg,.jpeg,.png">
                            <small class="form-text text-muted">
                                Si no sube un nuevo archivo, se mantendrá el actual.
                            </small>
                            @error('archivo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if($mascotaDocument->documentRequirement->tipo_validacion === 'fecha_vencimiento')
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fecha_emision">Fecha de Emisión</label>
                                    <input type="date" 
                                           class="form-control" 
                                           id="fecha_emision"
                                           name="fecha_emision"
                                           value="{{ $mascotaDocument->fecha_emision ? $mascotaDocument->fecha_emision->format('Y-m-d') : '' }}"
                                           max="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fecha_vencimiento">Fecha de Vencimiento</label>
                                    <input type="date" 
                                           class="form-control" 
                                           id="fecha_vencimiento"
                                           name="fecha_vencimiento"
                                           value="{{ $mascotaDocument->fecha_vencimiento ? $mascotaDocument->fecha_vencimiento->format('Y-m-d') : '' }}"
                                           min="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="form-group mb-3">
                            <label for="notas">Notas</label>
                            <textarea name="notas" 
                                      id="notas" 
                                      class="form-control" 
                                      rows="3">{{ old('notas', $mascotaDocument->notas) }}</textarea>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar Cambios
                            </button>
                            <a href="{{ route('mascota-documents.show', $mascotaDocument->id) }}" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


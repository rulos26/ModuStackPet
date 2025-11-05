@extends('layouts.app')

@section('template_title')
    Editar Requisito Documental
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span id="card_title">
                            <i class="fas fa-edit"></i> Editar Requisito Documental
                        </span>
                        <div class="float-right">
                            <a href="{{ route('admin.document-requirements.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.document-requirements.update', $documentRequirement->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-3">
                            <label for="codigo">Código *</label>
                            <input type="text" 
                                   class="form-control @error('codigo') is-invalid @enderror" 
                                   id="codigo"
                                   name="codigo"
                                   value="{{ old('codigo', $documentRequirement->codigo) }}"
                                   required>
                            @error('codigo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="nombre">Nombre *</label>
                            <input type="text" 
                                   class="form-control @error('nombre') is-invalid @enderror" 
                                   id="nombre"
                                   name="nombre"
                                   value="{{ old('nombre', $documentRequirement->nombre) }}"
                                   required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="descripcion">Descripción</label>
                            <textarea name="descripcion" 
                                      id="descripcion" 
                                      class="form-control" 
                                      rows="3">{{ old('descripcion', $documentRequirement->descripcion) }}</textarea>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="obligatorio"
                                           name="obligatorio"
                                           value="1"
                                           {{ old('obligatorio', $documentRequirement->obligatorio) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="obligatorio">
                                        Obligatorio
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="activo"
                                           name="activo"
                                           value="1"
                                           {{ old('activo', $documentRequirement->activo) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="activo">
                                        Activo
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="orden">Orden</label>
                            <input type="number" 
                                   class="form-control" 
                                   id="orden"
                                   name="orden"
                                   value="{{ old('orden', $documentRequirement->orden) }}"
                                   min="0">
                        </div>

                        <div class="form-group mb-3">
                            <label for="tipo_validacion">Tipo de Validación</label>
                            <select name="tipo_validacion" 
                                    id="tipo_validacion" 
                                    class="form-control">
                                <option value="">Ninguna</option>
                                <option value="fecha_vencimiento" {{ old('tipo_validacion', $documentRequirement->tipo_validacion) === 'fecha_vencimiento' ? 'selected' : '' }}>Fecha de Vencimiento</option>
                                <option value="firma_digital" {{ old('tipo_validacion', $documentRequirement->tipo_validacion) === 'firma_digital' ? 'selected' : '' }}>Firma Digital</option>
                                <option value="sello_veterinario" {{ old('tipo_validacion', $documentRequirement->tipo_validacion) === 'sello_veterinario' ? 'selected' : '' }}>Sello Veterinario</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="dias_validez">Días de Validez</label>
                            <input type="number" 
                                   class="form-control" 
                                   id="dias_validez"
                                   name="dias_validez"
                                   value="{{ old('dias_validez', $documentRequirement->dias_validez) }}"
                                   min="1">
                        </div>

                        <div class="form-group mb-3">
                            <label>Formatos Permitidos</label>
                            @php
                                $formatosSeleccionados = old('formatos_permitidos', $documentRequirement->formatos_permitidos ?? ['pdf', 'jpg', 'jpeg', 'png']);
                            @endphp
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="formatos_permitidos[]" value="pdf" id="formato_pdf" {{ in_array('pdf', $formatosSeleccionados) ? 'checked' : '' }}>
                                <label class="form-check-label" for="formato_pdf">PDF</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="formatos_permitidos[]" value="jpg" id="formato_jpg" {{ in_array('jpg', $formatosSeleccionados) ? 'checked' : '' }}>
                                <label class="form-check-label" for="formato_jpg">JPG</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="formatos_permitidos[]" value="jpeg" id="formato_jpeg" {{ in_array('jpeg', $formatosSeleccionados) ? 'checked' : '' }}>
                                <label class="form-check-label" for="formato_jpeg">JPEG</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="formatos_permitidos[]" value="png" id="formato_png" {{ in_array('png', $formatosSeleccionados) ? 'checked' : '' }}>
                                <label class="form-check-label" for="formato_png">PNG</label>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="tamaño_maximo_kb">Tamaño Máximo (KB)</label>
                            <input type="number" 
                                   class="form-control" 
                                   id="tamaño_maximo_kb"
                                   name="tamaño_maximo_kb"
                                   value="{{ old('tamaño_maximo_kb', $documentRequirement->tamaño_maximo_kb) }}"
                                   min="1">
                        </div>

                        <div class="form-group mb-3">
                            <label for="motivo">Motivo del Cambio (opcional)</label>
                            <textarea name="motivo" 
                                      id="motivo" 
                                      class="form-control" 
                                      rows="2" 
                                      placeholder="Explique el motivo del cambio (se registrará en el log)">{{ old('motivo') }}</textarea>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="aplica_razas_peligrosas"
                                   name="aplica_razas_peligrosas"
                                   value="1"
                                   {{ old('aplica_razas_peligrosas', $documentRequirement->aplica_razas_peligrosas) ? 'checked' : '' }}>
                            <label class="form-check-label" for="aplica_razas_peligrosas">
                                Aplica solo para razas peligrosas
                            </label>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar Cambios
                            </button>
                            <a href="{{ route('admin.document-requirements.index') }}" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


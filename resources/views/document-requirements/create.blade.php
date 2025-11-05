@extends('layouts.app')

@section('template_title')
    Crear Requisito Documental
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span id="card_title">
                            <i class="fas fa-plus"></i> Crear Requisito Documental
                        </span>
                        <div class="float-right">
                            <a href="{{ route('admin.document-requirements.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.document-requirements.store') }}" method="POST">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="codigo">Código *</label>
                            <input type="text" 
                                   class="form-control @error('codigo') is-invalid @enderror" 
                                   id="codigo"
                                   name="codigo"
                                   value="{{ old('codigo') }}"
                                   required>
                            <small class="form-text text-muted">Código único del requisito (ej: VAC, DESP, SALUD)</small>
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
                                   value="{{ old('nombre') }}"
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
                                      rows="3">{{ old('descripcion') }}</textarea>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="obligatorio"
                                           name="obligatorio"
                                           value="1"
                                           {{ old('obligatorio', true) ? 'checked' : '' }}>
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
                                           {{ old('activo', true) ? 'checked' : '' }}>
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
                                   value="{{ old('orden', 0) }}"
                                   min="0">
                        </div>

                        <div class="form-group mb-3">
                            <label for="tipo_validacion">Tipo de Validación</label>
                            <select name="tipo_validacion" 
                                    id="tipo_validacion" 
                                    class="form-control">
                                <option value="">Ninguna</option>
                                <option value="fecha_vencimiento" {{ old('tipo_validacion') === 'fecha_vencimiento' ? 'selected' : '' }}>Fecha de Vencimiento</option>
                                <option value="firma_digital" {{ old('tipo_validacion') === 'firma_digital' ? 'selected' : '' }}>Firma Digital</option>
                                <option value="sello_veterinario" {{ old('tipo_validacion') === 'sello_veterinario' ? 'selected' : '' }}>Sello Veterinario</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="dias_validez">Días de Validez</label>
                            <input type="number" 
                                   class="form-control" 
                                   id="dias_validez"
                                   name="dias_validez"
                                   value="{{ old('dias_validez') }}"
                                   min="1">
                            <small class="form-text text-muted">Ej: 365 para vacunas (1 año), 90 para desparasitación (3 meses)</small>
                        </div>

                        <div class="form-group mb-3">
                            <label>Formatos Permitidos</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="formatos_permitidos[]" value="pdf" id="formato_pdf" {{ in_array('pdf', old('formatos_permitidos', ['pdf', 'jpg', 'jpeg', 'png'])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="formato_pdf">PDF</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="formatos_permitidos[]" value="jpg" id="formato_jpg" {{ in_array('jpg', old('formatos_permitidos', ['pdf', 'jpg', 'jpeg', 'png'])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="formato_jpg">JPG</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="formatos_permitidos[]" value="jpeg" id="formato_jpeg" {{ in_array('jpeg', old('formatos_permitidos', ['pdf', 'jpg', 'jpeg', 'png'])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="formato_jpeg">JPEG</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="formatos_permitidos[]" value="png" id="formato_png" {{ in_array('png', old('formatos_permitidos', ['pdf', 'jpg', 'jpeg', 'png'])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="formato_png">PNG</label>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="tamaño_maximo_kb">Tamaño Máximo (KB)</label>
                            <input type="number" 
                                   class="form-control" 
                                   id="tamaño_maximo_kb"
                                   name="tamaño_maximo_kb"
                                   value="{{ old('tamaño_maximo_kb', 2048) }}"
                                   min="1">
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="aplica_razas_peligrosas"
                                   name="aplica_razas_peligrosas"
                                   value="1"
                                   {{ old('aplica_razas_peligrosas') ? 'checked' : '' }}>
                            <label class="form-check-label" for="aplica_razas_peligrosas">
                                Aplica solo para razas peligrosas
                            </label>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Crear Requisito
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


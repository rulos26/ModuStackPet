<div class="box box-info padding-1">
    <div class="box-body">
        <div class="form-group mb-3">
            <label for="nombre" class="form-label">Nombre del Departamento</label>
            <input type="text"
                   name="nombre"
                   id="nombre"
                   class="form-control @error('nombre') is-invalid @enderror"
                   placeholder="Ingrese el nombre del departamento"
                   value="{{ old('nombre', $departamento->nombre ?? '') }}"
                   required
                   autofocus>
            @error('nombre')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="estado" id="estado" value="1"
                       {{ old('estado', $departamento->estado ?? 1) == 1 ? 'checked' : '' }}>
                <label class="form-check-label" for="estado">Activo</label>
            </div>
            @error('estado')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
    <div class="box-footer mt-3">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i>
            {{ $departamento->exists ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('departamentos.index') }}" class="btn btn-secondary">
            <i class="fas fa-times"></i>
            Cancelar
        </a>
    </div>
</div>

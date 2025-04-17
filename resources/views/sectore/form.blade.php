<div class="row padding-1 p-1">
    <div class="col-md-12">
        <div class="form-group">
            <label for="nombre" class="form-label">{{ __('Nombre del Sector') }}</label>
            <input type="text" name="nombre" id="nombre" class="form-control @error('nombre') is-invalid @enderror"
                   value="{{ old('nombre', $sectore->nombre ?? '') }}" required autofocus>
            @error('nombre')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
        <a href="{{ route('sectores.index') }}" class="btn btn-secondary">{{ __('Cancelar') }}</a>
    </div>
</div>

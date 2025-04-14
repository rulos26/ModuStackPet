<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>{{ __('Municipio') }}:</strong>
            <input type="text" name="municipio" class="form-control @error('municipio') is-invalid @enderror"
                   value="{{ old('municipio', $ciudad->municipio ?? '') }}" placeholder="{{ __('Nombre del municipio') }}" required>
            @error('municipio')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>{{ __('Departamento') }}:</strong>
            <select name="departamento_id" class="form-control @error('departamento_id') is-invalid @enderror" required>
                <option value="">{{ __('Seleccione un departamento') }}</option>
                @foreach($departamentos as $id => $nombre)
                    <option value="{{ $id }}" {{ old('departamento_id', $ciudad->departamento_id ?? '') == $id ? 'selected' : '' }}>
                        {{ $nombre }}
                    </option>
                @endforeach
            </select>
            @error('departamento_id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="estado" id="estado" value="1"
                       {{ old('estado', $ciudad->estado ?? 1) == 1 ? 'checked' : '' }}>
                <label class="form-check-label" for="estado">{{ __('Activo') }}</label>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 text-center mt-3">
        <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
        <a class="btn btn-secondary" href="{{ route('ciudades.index') }}">{{ __('Cancelar') }}</a>
    </div>
</div>

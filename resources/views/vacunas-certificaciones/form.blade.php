@csrf

<!-- Selección de mascota -->
<div class="form-group row">
    <label for="mascota_id" class="col-md-4 col-form-label text-md-right">{{ __('Mascota') }}</label>
    <div class="col-md-6">
        <select name="mascota_id" id="mascota_id" class="form-control @error('mascota_id') is-invalid @enderror" required>
            <option value="">{{ __('Seleccione una mascota') }}</option>
            @foreach($mascotas as $mascota)
                <option value="{{ $mascota->id }}" {{ (old('mascota_id', $vacunaCertificacion->mascota_id ?? '') == $mascota->id) ? 'selected' : '' }}>
                    {{ $mascota->nombre }}
                </option>
            @endforeach
        </select>
        @error('mascota_id')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>

<!-- Fecha de última vacuna -->
<div class="form-group row">
    <label for="fecha_ultima_vacuna" class="col-md-4 col-form-label text-md-right">{{ __('Fecha Última Vacuna') }}</label>
    <div class="col-md-6">
        <input type="date" name="fecha_ultima_vacuna" id="fecha_ultima_vacuna"
               class="form-control @error('fecha_ultima_vacuna') is-invalid @enderror"
               value="{{ old('fecha_ultima_vacuna', $vacunaCertificacion->fecha_ultima_vacuna ?? '') }}">
        @error('fecha_ultima_vacuna')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>

<!-- Operaciones realizadas -->
<div class="form-group row">
    <label for="operaciones" class="col-md-4 col-form-label text-md-right">{{ __('Operaciones') }}</label>
    <div class="col-md-6">
        <textarea name="operaciones" id="operaciones" rows="3"
                  class="form-control @error('operaciones') is-invalid @enderror"
                  placeholder="{{ __('Describa las operaciones realizadas') }}">{{ old('operaciones', $vacunaCertificacion->operaciones ?? '') }}</textarea>
        @error('operaciones')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>

<!-- Certificado veterinario -->
<div class="form-group row">
    <label for="certificado_veterinario" class="col-md-4 col-form-label text-md-right">{{ __('Certificado Veterinario') }}</label>
    <div class="col-md-6">
        <input type="file" name="certificado_veterinario" id="certificado_veterinario"
               class="form-control-file @error('certificado_veterinario') is-invalid @enderror"
               accept=".pdf,.jpg,.jpeg,.png">
        @error('certificado_veterinario')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        @if(isset($vacunaCertificacion) && $vacunaCertificacion->certificado_veterinario)
            <div class="mt-2">
                <a href="{{ $vacunaCertificacion->certificado_veterinario_url }}" target="_blank" class="btn btn-sm btn-info">
                    <i class="fas fa-eye"></i> {{ __('Ver documento actual') }}
                </a>
            </div>
        @endif
        <small class="form-text text-muted">
            {{ __('Formatos aceptados: PDF, JPG, JPEG, PNG. Tamaño máximo: 2MB') }}
        </small>
    </div>
</div>

<!-- Cédula del propietario -->
<div class="form-group row">
    <label for="cedula_propietario" class="col-md-4 col-form-label text-md-right">{{ __('Cédula Propietario') }}</label>
    <div class="col-md-6">
        <input type="file" name="cedula_propietario" id="cedula_propietario"
               class="form-control-file @error('cedula_propietario') is-invalid @enderror"
               accept=".pdf,.jpg,.jpeg,.png">
        @error('cedula_propietario')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        @if(isset($vacunaCertificacion) && $vacunaCertificacion->cedula_propietario)
            <div class="mt-2">
                <a href="{{ $vacunaCertificacion->cedula_propietario_url }}" target="_blank" class="btn btn-sm btn-info">
                    <i class="fas fa-eye"></i> {{ __('Ver documento actual') }}
                </a>
            </div>
        @endif
        <small class="form-text text-muted">
            {{ __('Formatos aceptados: PDF, JPG, JPEG, PNG. Tamaño máximo: 2MB') }}
        </small>
    </div>
</div>

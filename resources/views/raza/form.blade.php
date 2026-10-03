@if ($errors->any())
<div class="alert alert-danger">
    <strong>{{ __('¡Ups!') }}</strong> {{ __('Hay algunos problemas con los datos ingresados.') }}<br><br>
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<!-- Sección: Datos de la Raza -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">{{ __('Datos de la Raza') }}</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <!-- Tipo de Mascota -->
                <div class="form-group mb-3">
                    <label for="tipo_mascota" class="form-label">{{ __('Tipo de Mascota') }}</label>
                    <input type="text" name="tipo_mascota" class="form-control @error('tipo_mascota') is-invalid @enderror"
                        id="tipo_mascota" placeholder="{{ __('Tipo de mascota') }}"
                        value="{{ old('tipo_mascota', $raza->tipo_mascota ?? '') }}" required>
                    {!! $errors->first('tipo_mascota', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>

            <div class="col-md-6">
                <!-- Nombre de la Raza -->
                <div class="form-group mb-3">
                    <label for="nombre" class="form-label">{{ __('Nombre de la Raza') }}</label>
                    <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                        id="nombre" placeholder="{{ __('Nombre de la raza') }}"
                        value="{{ old('nombre', $raza->nombre ?? '') }}" required>
                    {!! $errors->first('nombre', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-check mb-3">
                    <input type="checkbox" name="requiere_cuidado_especial" value="1"
                        class="form-check-input @error('requiere_cuidado_especial') is-invalid @enderror"
                        id="requiere_cuidado_especial"
                        {{ old('requiere_cuidado_especial', $raza->requiere_cuidado_especial ?? false) ? 'checked' : '' }}>
                    <label for="requiere_cuidado_especial" class="form-check-label">
                        {{ __('Requiere cuidado especial al manejarla') }}
                    </label>
                    {!! $errors->first('requiere_cuidado_especial', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="cuidados_especiales" class="form-label">{{ __('Indicaciones de cuidado') }}</label>
                    <textarea name="cuidados_especiales" rows="3"
                        class="form-control @error('cuidados_especiales') is-invalid @enderror"
                        id="cuidados_especiales"
                        placeholder="{{ __('Ejemplo: usar bozal y correa corta') }}">{{ old('cuidados_especiales', $raza->cuidados_especiales ?? '') }}</textarea>
                    {!! $errors->first('cuidados_especiales', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12 text-center">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-fw fa-save"></i> {{ __('Guardar') }}
                </button>
                <a class="btn btn-secondary" href="{{ route('razas.index') }}">
                    <i class="fa fa-fw fa-times"></i> {{ __('Cancelar') }}
                </a>
            </div>
        </div>
    </div>
</div>

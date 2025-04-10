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

<!-- Sección: Datos Básicos -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">{{ __('Datos Básicos') }}</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <!-- Nombre -->
                <div class="form-group mb-3">
                    <label for="nombre" class="form-label">{{ __('Nombre') }}</label>
                    <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                        id="nombre" placeholder="{{ __('Nombre de la mascota') }}"
                        value="{{ old('nombre', $mascota->nombre ?? '') }}" required>
                    {!! $errors->first('nombre', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>

            <div class="col-md-6">
                <!-- Edad -->
                <div class="form-group mb-3">
                    <label for="edad" class="form-label">{{ __('Edad') }}</label>
                    <input type="number" name="edad" class="form-control @error('edad') is-invalid @enderror"
                        id="edad" placeholder="{{ __('Edad de la mascota') }}"
                        value="{{ old('edad', $mascota->edad ?? '') }}" min="0" max="30">
                    {!! $errors->first('edad', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>

            <div class="col-md-6">
                <!-- Fecha de Nacimiento -->
                <div class="form-group mb-3">
                    <label for="fecha_nacimiento" class="form-label">{{ __('Fecha de Nacimiento') }}</label>
                    <input type="date" name="fecha_nacimiento" class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                        id="fecha_nacimiento" value="{{ old('fecha_nacimiento', $mascota->fecha_nacimiento ?? '') }}">
                    {!! $errors->first('fecha_nacimiento', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>

            <div class="col-md-6">
                <!-- Género -->
                <div class="form-group mb-3">
                    <label for="genero" class="form-label">{{ __('Género') }}</label>
                    <select name="genero" class="form-select @error('genero') is-invalid @enderror" id="genero" required>
                        <option value="">-- {{ __('Seleccione un género') }} --</option>
                        <option value="Macho" {{ old('genero', $mascota->genero ?? '') == 'Macho' ? 'selected' : '' }}>{{ __('Macho') }}</option>
                        <option value="Hembra" {{ old('genero', $mascota->genero ?? '') == 'Hembra' ? 'selected' : '' }}>{{ __('Hembra') }}</option>
                    </select>
                    {!! $errors->first('genero', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sección: Información de Raza y Ubicación -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">{{ __('Información de Raza y Ubicación') }}</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <!-- Raza -->
                <div class="form-group mb-3">
                    <label for="raza_id" class="form-label">{{ __('Raza') }}</label>
                    <select name="raza_id" class="form-select @error('raza_id') is-invalid @enderror" id="raza_id" required>
                        <option value="">-- {{ __('Seleccione una raza') }} --</option>
                        @foreach($razas as $raza)
                            <option value="{{ $raza->id }}" {{ old('raza_id', $mascota->raza_id ?? '') == $raza->id ? 'selected' : '' }}>
                                {{ $raza->nombre }} ({{ $raza->tipo_mascota }})
                            </option>
                        @endforeach
                    </select>
                    {!! $errors->first('raza_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>

            <div class="col-md-6">
                <!-- Barrio -->
                <div class="form-group mb-3">
                    <label for="barrio_id" class="form-label">{{ __('Barrio') }}</label>
                    <select name="barrio_id" class="form-select @error('barrio_id') is-invalid @enderror" id="barrio_id" required>
                        <option value="">-- {{ __('Seleccione un barrio') }} --</option>
                        @foreach($barrios as $barrio)
                            <option value="{{ $barrio->id }}" {{ old('barrio_id', $mascota->barrio_id ?? '') == $barrio->id ? 'selected' : '' }}>
                                {{ $barrio->nombre }} ({{ $barrio->localidad }})
                            </option>
                        @endforeach
                    </select>
                    {!! $errors->first('barrio_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>

            <div class="col-md-6">
                <!-- Dirección -->
                <div class="form-group mb-3">
                    <label for="direccion" class="form-label">{{ __('Dirección') }}</label>
                    <input type="text" name="direccion" class="form-control @error('direccion') is-invalid @enderror"
                        id="direccion" placeholder="{{ __('Dirección de la mascota') }}"
                        value="{{ old('direccion', $mascota->direccion ?? '') }}" required>
                    {!! $errors->first('direccion', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>

            <div class="col-md-6">
                <!-- Interior/Apartamento -->
                <div class="form-group mb-3">
                    <label for="interior_apto" class="form-label">{{ __('Interior/Apartamento') }}</label>
                    <input type="text" name="interior_apto" class="form-control @error('interior_apto') is-invalid @enderror"
                        id="interior_apto" placeholder="{{ __('Interior o apartamento') }}"
                        value="{{ old('interior_apto', $mascota->interior_apto ?? '') }}">
                    {!! $errors->first('interior_apto', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sección: Información de Salud -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">{{ __('Información de Salud') }}</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <!-- Vacunas Completas -->
                <div class="form-group mb-3">
                    <label class="form-label">{{ __('Vacunas Completas') }}</label>
                    <div class="form-check">
                        <input type="checkbox" name="vacunas_completas" class="form-check-input @error('vacunas_completas') is-invalid @enderror"
                            id="vacunas_completas" value="1" {{ old('vacunas_completas', $mascota->vacunas_completas ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label" for="vacunas_completas">{{ __('Sí') }}</label>
                        {!! $errors->first('vacunas_completas', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <!-- Última Vacunación -->
                <div class="form-group mb-3">
                    <label for="ultima_vacunacion" class="form-label">{{ __('Última Vacunación') }}</label>
                    <input type="date" name="ultima_vacunacion" class="form-control @error('ultima_vacunacion') is-invalid @enderror"
                        id="ultima_vacunacion" value="{{ old('ultima_vacunacion', $mascota->ultima_vacunacion ?? '') }}">
                    {!! $errors->first('ultima_vacunacion', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>

            <div class="col-md-6">
                <!-- Esterilizado -->
                <div class="form-group mb-3">
                    <label class="form-label">{{ __('Esterilizado') }}</label>
                    <div class="form-check">
                        <input type="checkbox" name="esterilizado" class="form-check-input @error('esterilizado') is-invalid @enderror"
                            id="esterilizado" value="1" {{ old('esterilizado', $mascota->esterilizado ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label" for="esterilizado">{{ __('Sí') }}</label>
                        {!! $errors->first('esterilizado', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Botones de Acción -->
<div class="row mt-4">
    <div class="col-12 text-center">
        <button type="submit" class="btn btn-primary">
            <i class="fa fa-fw fa-save"></i> {{ __('Guardar') }}
        </button>
        <a class="btn btn-secondary" href="{{ route('mascotas.index') }}">
            <i class="fa fa-fw fa-times"></i> {{ __('Cancelar') }}
        </a>
    </div>
</div>

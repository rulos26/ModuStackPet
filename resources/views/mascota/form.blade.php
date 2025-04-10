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

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6">
        <div class="form-group">
            <strong>{{ __('Nombre:') }}</strong>
            <input type="text" name="nombre" class="form-control" placeholder="{{ __('Nombre de la mascota') }}" value="{{ old('nombre', $mascota->nombre ?? '') }}">
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6">
        <div class="form-group">
            <strong>{{ __('Edad:') }}</strong>
            <input type="number" name="edad" class="form-control" placeholder="{{ __('Edad de la mascota') }}" value="{{ old('edad', $mascota->edad ?? '') }}">
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6">
        <div class="form-group">
            <strong>{{ __('Fecha de Nacimiento:') }}</strong>
            <input type="date" name="fecha_nacimiento" class="form-control" value="{{ old('fecha_nacimiento', $mascota->fecha_nacimiento ?? '') }}">
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6">
        <div class="form-group">
            <strong>{{ __('Género:') }}</strong>
            <select name="genero" class="form-control">
                <option value="">-- {{ __('Seleccione un género') }} --</option>
                <option value="Macho" {{ old('genero', $mascota->genero ?? '') == 'Macho' ? 'selected' : '' }}>{{ __('Macho') }}</option>
                <option value="Hembra" {{ old('genero', $mascota->genero ?? '') == 'Hembra' ? 'selected' : '' }}>{{ __('Hembra') }}</option>
            </select>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6">
        <div class="form-group">
            <strong>{{ __('Raza:') }}</strong>
            <select name="raza_id" class="form-control">
                <option value="">-- {{ __('Seleccione una raza') }} --</option>
                @foreach($razas as $raza)
                    <option value="{{ $raza->id }}" {{ old('raza_id', $mascota->raza_id ?? '') == $raza->id ? 'selected' : '' }}>
                        {{ $raza->nombre }} ({{ $raza->tipo_mascota }})
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6">
        <div class="form-group">
            <strong>{{ __('Barrio:') }}</strong>
            <select name="barrio_id" class="form-control">
                <option value="">-- {{ __('Seleccione un barrio') }} --</option>
                @foreach($barrios as $barrio)
                    <option value="{{ $barrio->id }}" {{ old('barrio_id', $mascota->barrio_id ?? '') == $barrio->id ? 'selected' : '' }}>
                        {{ $barrio->nombre }} ({{ $barrio->localidad }})
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6">
        <div class="form-group">
            <strong>{{ __('Dirección:') }}</strong>
            <input type="text" name="direccion" class="form-control" placeholder="{{ __('Dirección de la mascota') }}" value="{{ old('direccion', $mascota->direccion ?? '') }}">
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6">
        <div class="form-group">
            <strong>{{ __('Interior/Apartamento:') }}</strong>
            <input type="text" name="interior_apto" class="form-control" placeholder="{{ __('Interior o apartamento') }}" value="{{ old('interior_apto', $mascota->interior_apto ?? '') }}">
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6">
        <div class="form-group">
            <strong>{{ __('Vacunas Completas:') }}</strong>
            <div class="form-check">
                <input type="checkbox" name="vacunas_completas" class="form-check-input" value="1" {{ old('vacunas_completas', $mascota->vacunas_completas ?? false) ? 'checked' : '' }}>
                <label class="form-check-label">{{ __('Sí') }}</label>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6">
        <div class="form-group">
            <strong>{{ __('Última Vacunación:') }}</strong>
            <input type="date" name="ultima_vacunacion" class="form-control" value="{{ old('ultima_vacunacion', $mascota->ultima_vacunacion ?? '') }}">
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6">
        <div class="form-group">
            <strong>{{ __('Esterilizado:') }}</strong>
            <div class="form-check">
                <input type="checkbox" name="esterilizado" class="form-check-input" value="1" {{ old('esterilizado', $mascota->esterilizado ?? false) ? 'checked' : '' }}>
                <label class="form-check-label">{{ __('Sí') }}</label>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6">
        <div class="form-group">
            <strong>{{ __('Último Examen Médico:') }}</strong>
            <input type="date" name="ultimo_examen_medico" class="form-control" value="{{ old('ultimo_examen_medico', $mascota->ultimo_examen_medico ?? '') }}">
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>{{ __('Comportamiento:') }}</strong>
            <textarea name="comportamiento" class="form-control" rows="3" placeholder="{{ __('Describa el comportamiento de la mascota') }}">{{ old('comportamiento', $mascota->comportamiento ?? '') }}</textarea>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>{{ __('Recomendaciones:') }}</strong>
            <textarea name="recomendaciones" class="form-control" rows="3" placeholder="{{ __('Recomendaciones para el cuidado de la mascota') }}">{{ old('recomendaciones', $mascota->recomendaciones ?? '') }}</textarea>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>{{ __('Enfermedades:') }}</strong>
            <textarea name="enfermedades" class="form-control" rows="3" placeholder="{{ __('Enfermedades o condiciones médicas') }}">{{ old('enfermedades', $mascota->enfermedades ?? '') }}</textarea>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>{{ __('Avatar:') }}</strong>
            <input type="file" name="avatar" class="form-control-file">
            @if(isset($mascota) && $mascota->avatar)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $mascota->avatar) }}" alt="Avatar de {{ $mascota->nombre }}" class="img-thumbnail" style="max-width: 150px;">
                </div>
            @endif
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
        <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
        <a class="btn btn-secondary" href="{{ route('mascotas.index') }}">{{ __('Cancelar') }}</a>
    </div>
</div>

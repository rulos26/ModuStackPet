<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="cedula_numero" class="form-label">{{ __('Cedula Numero') }}</label>
            <input type="text" name="cedula_numero" class="form-control @error('cedula_numero') is-invalid @enderror" value="{{ old('cedula_numero', $verificacione?->cedula_numero) }}" id="cedula_numero" placeholder="Cedula Numero">
            {!! $errors->first('cedula_numero', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="cedula_afiliado" class="form-label">{{ __('Cedula Afiliado') }}</label>
            <input type="text" name="cedula_afiliado" class="form-control @error('cedula_afiliado') is-invalid @enderror" value="{{ old('cedula_afiliado', $verificacione?->cedula_afiliado) }}" id="cedula_afiliado" placeholder="Cedula Afiliado">
            {!! $errors->first('cedula_afiliado', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="registro_civil_nacimiento_afiliado" class="form-label">{{ __('Registro Civil Nacimiento Afiliado') }}</label>
            <input type="text" name="registro_civil_nacimiento_afiliado" class="form-control @error('registro_civil_nacimiento_afiliado') is-invalid @enderror" value="{{ old('registro_civil_nacimiento_afiliado', $verificacione?->registro_civil_nacimiento_afiliado) }}" id="registro_civil_nacimiento_afiliado" placeholder="Registro Civil Nacimiento Afiliado">
            {!! $errors->first('registro_civil_nacimiento_afiliado', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="registro_defuncion_afiliado" class="form-label">{{ __('Registro Defuncion Afiliado') }}</label>
            <input type="text" name="registro_defuncion_afiliado" class="form-control @error('registro_defuncion_afiliado') is-invalid @enderror" value="{{ old('registro_defuncion_afiliado', $verificacione?->registro_defuncion_afiliado) }}" id="registro_defuncion_afiliado" placeholder="Registro Defuncion Afiliado">
            {!! $errors->first('registro_defuncion_afiliado', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="cedula_reclamante" class="form-label">{{ __('Cedula Reclamante') }}</label>
            <input type="text" name="cedula_reclamante" class="form-control @error('cedula_reclamante') is-invalid @enderror" value="{{ old('cedula_reclamante', $verificacione?->cedula_reclamante) }}" id="cedula_reclamante" placeholder="Cedula Reclamante">
            {!! $errors->first('cedula_reclamante', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="registro_civil_nacimiento_descendiente" class="form-label">{{ __('Registro Civil Nacimiento Descendiente') }}</label>
            <input type="text" name="registro_civil_nacimiento_descendiente" class="form-control @error('registro_civil_nacimiento_descendiente') is-invalid @enderror" value="{{ old('registro_civil_nacimiento_descendiente', $verificacione?->registro_civil_nacimiento_descendiente) }}" id="registro_civil_nacimiento_descendiente" placeholder="Registro Civil Nacimiento Descendiente">
            {!! $errors->first('registro_civil_nacimiento_descendiente', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Siguiente') }}</button>
    </div>
</div>
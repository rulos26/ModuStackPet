<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <input type="hidden" name="cedula_numero" value="{{ $cedula ?? old('cedula_numero') }}">
        <div class="form-group mb-2 mb20">
            <label for="documentos" class="form-label">{{ __('Documentos aportados a la reclamación auténticos') }}</label>
            <select name="documentos" id="documentos" class="form-control @error('documentos') is-invalid @enderror">
                <option value="1" {{ old('documentos', $conclusione?->documentos) == 1 ? 'selected' : '' }}>Son</option>
                <option value="0" {{ old('documentos', $conclusione?->documentos) == 0 ? 'selected' : '' }}>No son</option>
            </select>
            {!! $errors->first('documentos', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        
        <div class="form-group mb-2 mb20">
            <label for="nexos" class="form-label">{{ __('Los nexos de parentesco entre el afiliado y la reclamante') }}</label>
            <select name="nexos" id="nexos" class="form-control @error('nexos') is-invalid @enderror">
                <option value="1" {{ old('nexos', $conclusione?->nexos) == 1 ? 'selected' : '' }}>Son</option>
                <option value="0" {{ old('nexos', $conclusione?->nexos) == 0 ? 'selected' : '' }}>No son</option>
            </select>
            {!! $errors->first('nexos', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        
        <div class="form-group mb-2 mb20">
            <label for="muerte_origen" class="form-label">{{ __('Muerte Origen') }}</label>
            <input type="text" name="muerte_origen" class="form-control @error('muerte_origen') is-invalid @enderror" value="{{ old('muerte_origen', $conclusione?->muerte_origen) }}" id="muerte_origen" placeholder="Muerte Origen">
            {!! $errors->first('muerte_origen', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="reclamante" class="form-label">{{ __('Reclamante') }}</label>
            <input type="text" name="reclamante" class="form-control @error('reclamante') is-invalid @enderror" value="{{ old('reclamante', $conclusione?->reclamante) }}" id="reclamante" placeholder="Reclamante">
            {!! $errors->first('reclamante', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="nombre_reclamante" class="form-label">{{ __('Nombre Reclamante') }}</label>
            <input type="text" name="nombre_reclamante" class="form-control @error('nombre_reclamante') is-invalid @enderror" value="{{ old('nombre_reclamante', $conclusione?->nombre_reclamante) }}" id="nombre_reclamante" placeholder="Nombre Reclamante">
            {!! $errors->first('nombre_reclamante', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="afiliado_deja_descendiente" class="form-label">{{ __('Afiliado Deja Descendiente') }}</label>
            <input type="text" name="afiliado_deja_descendiente" class="form-control @error('afiliado_deja_descendiente') is-invalid @enderror" value="{{ old('afiliado_deja_descendiente', $conclusione?->afiliado_deja_descendiente) }}" id="afiliado_deja_descendiente" placeholder="Afiliado Deja Descendiente">
            {!! $errors->first('afiliado_deja_descendiente', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="descendientes_relacion" class="form-label">{{ __('Descendientes Relacion') }}</label>
            <input type="text" name="descendientes_relacion" class="form-control @error('descendientes_relacion') is-invalid @enderror" value="{{ old('descendientes_relacion', $conclusione?->descendientes_relacion) }}" id="descendientes_relacion" placeholder="Descendientes Relacion">
            {!! $errors->first('descendientes_relacion', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="descendientes_afiliado" class="form-label">{{ __('Descendientes Afiliado') }}</label>
            <input type="text" name="descendientes_afiliado" class="form-control @error('descendientes_afiliado') is-invalid @enderror" value="{{ old('descendientes_afiliado', $conclusione?->descendientes_afiliado) }}" id="descendientes_afiliado" placeholder="Descendientes Afiliado">
            {!! $errors->first('descendientes_afiliado', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="datos_hijo" class="form-label">{{ __('Datos Hijo') }}</label>
            <input type="text" name="datos_hijo" class="form-control @error('datos_hijo') is-invalid @enderror" value="{{ old('datos_hijo', $conclusione?->datos_hijo) }}" id="datos_hijo" placeholder="Datos Hijo">
            {!! $errors->first('datos_hijo', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="presenta_condicion_discapacidad" class="form-label">{{ __('Presenta Condicion Discapacidad') }}</label>
            <input type="text" name="presenta_condicion_discapacidad" class="form-control @error('presenta_condicion_discapacidad') is-invalid @enderror" value="{{ old('presenta_condicion_discapacidad', $conclusione?->presenta_condicion_discapacidad) }}" id="presenta_condicion_discapacidad" placeholder="Presenta Condicion Discapacidad">
            {!! $errors->first('presenta_condicion_discapacidad', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="observaciones" class="form-label">{{ __('Observaciones') }}</label>
            <input type="text" name="observaciones" class="form-control @error('observaciones') is-invalid @enderror" value="{{ old('observaciones', $conclusione?->observaciones) }}" id="observaciones" placeholder="Observaciones">
            {!! $errors->first('observaciones', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Siguiente') }}</button>
    </div>
</div>
<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="tipo_afiliacion" class="form-label">{{ __('Tipo Afiliacion') }}</label>
            <input type="text" name="tipo_afiliacion" class="form-control @error('tipo_afiliacion') is-invalid @enderror" value="{{ old('tipo_afiliacion', $tiposAfiliacione?->tipo_afiliacion) }}" id="tipo_afiliacion" placeholder="Tipo Afiliacion">
            {!! $errors->first('tipo_afiliacion', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Siguiente') }}</button>
    </div>
</div>
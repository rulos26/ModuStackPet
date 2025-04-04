<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="cobertura" class="form-label">{{ __('Cobertura') }}</label>
            <input type="text" name="cobertura" class="form-control @error('cobertura') is-invalid @enderror" value="{{ old('cobertura', $coberturasSalud?->cobertura) }}" id="cobertura" placeholder="Cobertura">
            {!! $errors->first('cobertura', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Siguiente') }}</button>
    </div>
</div>
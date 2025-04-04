<div class="row padding-1 p-1">
    <div class="col-md-12">
        <input type="hidden" name="cedula_numero" value="{{ $cedula ?? old('cedula_numero') }}">
       {{--  <div class="form-group mb-2 mb20">
            <label for="cedula_numero" class="form-label">{{ __('Cedula Numero') }}</label>
            <input type="text" name="cedula_numero" class="form-control @error('cedula_numero') is-invalid @enderror" value="{{ old('cedula_numero', $hallazgosObservacione?->cedula_numero) }}" id="cedula_numero" placeholder="Cedula Numero">
            {!! $errors->first('cedula_numero', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div> --}}
        <div class="form-group mb-2 mb20">
            <label for="hallazgos" class="form-label">{{ __('Hallazgos') }}</label>
            <textarea name="hallazgos" class="form-control @error('hallazgos') is-invalid @enderror" id="hallazgos" rows="4" placeholder="Hallazgos">{{ old('hallazgos', $hallazgosObservacione?->hallazgos) }}</textarea>
            {!! $errors->first('hallazgos', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        
        <div class="form-group mb-2 mb20">
            <label for="observaciones" class="form-label">{{ __('Observaciones') }}</label>
            <textarea name="observaciones" class="form-control @error('observaciones') is-invalid @enderror" id="observaciones" rows="4" placeholder="Observaciones">{{ old('observaciones', $hallazgosObservacione?->observaciones) }}</textarea>
            {!! $errors->first('observaciones', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Siguiente') }}</button>
    </div>
</div>
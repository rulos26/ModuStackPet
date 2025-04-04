<div class="row padding-1 p-1">
    <div class="col-md-12">
        <input type="hidden" name="cedula_numero" value="{{ $cedula ?? old('cedula_numero') }}">
       {{--  <div class="form-group mb-2 mb20">
            <label for="cedula_numero" class="form-label">{{ __('Cedula Numero') }}</label>
            <input type="text" name="cedula_numero" class="form-control @error('cedula_numero') is-invalid @enderror" value="{{ old('cedula_numero', $afiliadosCoberturasSalud?->cedula_numero) }}" id="cedula_numero" placeholder="Cedula Numero">
            {!! $errors->first('cedula_numero', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div> --}}
        <div class="form-group mb-2 mb20">
            <label for="cobertura_salud" class="form-label">{{ __('Cobertura Salud') }}</label>
            <select name="cobertura_salud" id="cobertura_salud" class="form-control @error('cobertura_salud') is-invalid @enderror">
                <option value="">{{ __('Seleccione una opción') }}</option>
                @foreach($eps as $ep)
                    <option value="{{ $ep->id }}" {{ old('cobertura_salud', $afiliadosCoberturasSalud?->cobertura_salud) == $ep->id ? 'selected' : '' }}>
                        {{ $ep->cobertura }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('cobertura_salud', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        
        <div class="form-group mb-2 mb20">
            <label for="tipo_afiliacion" class="form-label">{{ __('Tipo Afiliación') }}</label>
            <select name="tipo_afiliacion" class="form-control @error('tipo_afiliacion') is-invalid @enderror" id="tipo_afiliacion">
                <option value="">{{ __('Seleccione una opción') }}</option>
                @foreach($tipo_afiliacion as $tipo)
                    <option value="{{ $tipo->id }}" {{ old('tipo_afiliacion', $afiliadosCoberturasSalud?->tipo_afiliacion) == $tipo->id ? 'selected' : '' }}>
                        {{ $tipo->tipo_afiliacion }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('tipo_afiliacion', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        
        
        <div class="form-group mb-2 mb20">
            <label for="regimen" class="form-label">{{ __('Régimen') }}</label>
            <select name="regimen" class="form-control @error('regimen') is-invalid @enderror" id="regimen">
                <option value="">{{ __('Seleccione una opción') }}</option>
                @foreach($regimen as $item)
                    <option value="{{ $item->id }}" {{ old('regimen', $afiliadosCoberturasSalud?->regimen) == $item->id ? 'selected' : '' }}>
                        {{ $item->nombre }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('regimen', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        
        <div class="form-group mb-2 mb20">
            <label for="desde" class="form-label">{{ __('Desde') }}</label>
            <input type="date" name="desde" class="form-control @error('desde') is-invalid @enderror" value="{{ old('desde', $afiliadosCoberturasSalud?->desde) }}" id="desde" placeholder="Desde">
            {!! $errors->first('desde', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="registra_beneficiarios" class="form-label">{{ __('Registra Beneficiarios') }}</label>
            <select name="registra_beneficiarios" id="registra_beneficiarios" class="form-control @error('registra_beneficiarios') is-invalid @enderror">
                <option value="Sí" {{ old('registra_beneficiarios', $afiliadosCoberturasSalud?->registra_beneficiarios) === 'Sí' ? 'selected' : '' }}>Sí</option>
                <option value="No" {{ old('registra_beneficiarios', $afiliadosCoberturasSalud?->registra_beneficiarios) === 'No' ? 'selected' : '' }}>No</option>
            </select>
            {!! $errors->first('registra_beneficiarios', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        
        
        <div class="form-group mb-2 mb20">
            <label for="quien_reclama_prestaciones_sociales" class="form-label">{{ __('Quien Reclama Prestaciones Sociales') }}</label>
            <input type="text" name="quien_reclama_prestaciones_sociales" class="form-control @error('quien_reclama_prestaciones_sociales') is-invalid @enderror" value="{{ old('quien_reclama_prestaciones_sociales', $afiliadosCoberturasSalud?->quien_reclama_prestaciones_sociales) }}" id="quien_reclama_prestaciones_sociales" placeholder="Quien Reclama Prestaciones Sociales">
            {!! $errors->first('quien_reclama_prestaciones_sociales', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Siguiente') }}</button>
    </div>
</div>
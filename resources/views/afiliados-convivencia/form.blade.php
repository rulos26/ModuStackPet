<div class="row padding-1 p-1">
    <div class="col-md-3">
        
        <input type="hidden" name="cedula_numero" value="{{$cedula}}">
{{--         <div class="form-group mb-2 mb20">
            <label for="cedula_numero" class="form-label">{{ __('Cedula Numero') }}</label>
            <input type="text" name="cedula_numero" class="form-control @error('cedula_numero') is-invalid @enderror" value="{{ old('cedula_numero', $afiliadosConvivencia?->cedula_numero) }}" id="cedula_numero" placeholder="Cedula Numero">
            {!! $errors->first('cedula_numero', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div> --}}
        
        <div class="form-group mb-2 mb20">
            <label for="relacion_con" class="form-label">{{ __('Relacion Con') }}</label>
            <input type="text" name="relacion_con" class="form-control @error('relacion_con') is-invalid @enderror" value="{{ old('relacion_con', $afiliadosConvivencia?->relacion_con) }}" id="relacion_con" placeholder="Relacion Con">
            {!! $errors->first('relacion_con', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
       
        <div class="form-group mb-2 mb20">
            <label for="hasta_convivencia" class="form-label">{{ __('Hasta Convivencia') }}</label>
            <input type="date" name="hasta_convivencia" class="form-control @error('hasta_convivencia') is-invalid @enderror" value="{{ old('hasta_convivencia', $afiliadosConvivencia?->hasta_convivencia) }}" id="hasta_convivencia" placeholder="Hasta Convivencia">
            {!! $errors->first('hasta_convivencia', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-3">
        
       
        <div class="form-group mb-2 mb20">
            <label for="estado_civil_al_siniestro" class="form-label">{{ __('Estado Civil Al Siniestro') }}</label>
            
            <select name="estado_civil_al_siniestro" class="form-control @error('estado_civil_al_siniestro') is-invalid @enderror" id="estado_civil_al_siniestro">
                <option value="">{{ __('Seleccione una opción') }}</option>
                @foreach($estado_c  as $estado)
                    <option value="{{ $estado->id }}" {{ old('estado_civil_al_siniestro', $afiliadosConvivencia?->estado_civil_al_siniestro) == $estado->id ? 'selected' : '' }}>
                        {{ $estado->estado_civil}}
                    </option>
                @endforeach
            </select>
        
            {!! $errors->first('estado_civil_al_siniestro', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        
       
        <div class="form-group mb-2 mb20">
            <label for="quien_convivía" class="form-label">{{ __('Quien Convivía') }}</label>
            <input type="text" name="quien_convivía" class="form-control @error('quien_convivía') is-invalid @enderror" value="{{ old('quien_convivía', $afiliadosConvivencia?->quien_convivía) }}" id="quien_convivía" placeholder="Quien Convivía">
            {!! $errors->first('quien_convivía', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
       

    </div>
    <div class="col-md-3">
        
        <div class="form-group mb-2 mb20">
            <label for="desde_estado_civil" class="form-label">{{ __('Desde Estado Civil') }}</label>
            <input type="date" name="desde_estado_civil" class="form-control @error('desde_estado_civil') is-invalid @enderror" value="{{ old('desde_estado_civil', $afiliadosConvivencia?->desde_estado_civil) }}" id="desde_estado_civil" placeholder="Desde Estado Civil">
            {!! $errors->first('desde_estado_civil', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
       
        <div class="form-group mb-2 mb20">
            <label for="tiempo_convivencia" class="form-label">{{ __('Tiempo Convivencia (años) ') }}</label>
            <input type="number" name="tiempo_convivencia" class="form-control @error('tiempo_convivencia') is-invalid @enderror" value="{{ old('tiempo_convivencia', $afiliadosConvivencia?->tiempo_convivencia) }}" id="tiempo_convivencia" placeholder="Tiempo Convivencia">
            {!! $errors->first('tiempo_convivencia', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
       

    </div>
    <div class="col-md-3">
        
        
        <div class="form-group mb-2 mb20">
            <label for="hasta_estado_civil" class="form-label">{{ __('Hasta Estado Civil') }}</label>
            <input type="date" name="hasta_estado_civil" class="form-control @error('hasta_estado_civil') is-invalid @enderror" value="{{ old('hasta_estado_civil', $afiliadosConvivencia?->hasta_estado_civil) }}" id="hasta_estado_civil" placeholder="Hasta Estado Civil">
            {!! $errors->first('hasta_estado_civil', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        
        <div class="form-group mb-2 mb20">
            <label for="desde_convivencia" class="form-label">{{ __('Desde Convivencia') }}</label>
            <input type="date" name="desde_convivencia" class="form-control @error('desde_convivencia') is-invalid @enderror" value="{{ old('desde_convivencia', $afiliadosConvivencia?->desde_convivencia) }}" id="desde_convivencia" placeholder="Desde Convivencia">
            {!! $errors->first('desde_convivencia', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
      

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Siguiente') }}</button>
    </div>
</div>
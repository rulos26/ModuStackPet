<div class="row padding-1 p-1">
    <div class="col-md-12">
        <input type="hidden" name="cedula_numero" value="{{$cedula}}">
      {{--   <div class="form-group mb-2 mb20">
            <label for="cedula_numero" class="form-label">{{ __('Cedula Numero') }}</label>
            <input type="text" name="cedula_numero" class="form-control @error('cedula_numero') is-invalid @enderror" value="{{ old('cedula_numero', $direccionesVivienda?->cedula_numero) }}" id="cedula_numero" placeholder="Cedula Numero">
            {!! $errors->first('cedula_numero', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div> --}}
       
      
        
        <div class="form-group mb-2 mb20">
            <label for="direccion_residencia" class="form-label">{{ __('Dirección Residencia') }}</label>
            <input type="text" name="direccion_residencia" class="form-control @error('direccion_residencia') is-invalid @enderror" value="{{ old('direccion_residencia', $direccionesVivienda?->direccion_residencia) }}" id="direccion_residencia" 
                placeholder="Ej: Calle 123 #45-67, Apto 101, Barrio La Felicidad, Bogotá, Colombia" 
                pattern="[a-zA-Z0-9\s,.-#áéíóúÁÉÍÓÚñÑ]+" 
                title="La dirección solo puede contener letras, números, espacios y los caracteres especiales , . - #" 
                minlength="10" 
                maxlength="200" 
                required 
                oninput="this.setCustomValidity('')"
                oninvalid="this.setCustomValidity('Por favor, ingrese una dirección válida. Ej: Calle 123 #45-67, Apto 101, Barrio La Felicidad, Bogotá, Colombia')">
            {!! $errors->first('direccion_residencia', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
                                
     {{--    <div class="form-group mb-2 mb20">
            <label for="tipo_de_vivienda" class="form-label">{{ __('Tipo De Vivienda') }}</label>
            <input type="text" name="tipo_de_vivienda" class="form-control @error('tipo_de_vivienda') is-invalid @enderror" value="{{ old('tipo_de_vivienda', $direccionesVivienda?->tipo_de_vivienda) }}" id="tipo_de_vivienda" placeholder="Tipo De Vivienda">
            {!! $errors->first('tipo_de_vivienda', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div> --}}
        <div class="form-group mb-2 mb20">
            <label for="tipo_de_vivienda" class="form-label">{{ __('Tipo De Vivienda') }}</label>
            <select name="tipo_de_vivienda" class="form-control @error('tipo_de_vivienda') is-invalid @enderror" id="tipo_de_vivienda">
                <option value="">{{ __('Seleccione Tipo De Vivienda') }}</option>
                @foreach($tipos_vivienda as $tipo)
                    <option value="{{ $tipo->id }}" {{ old('tipo_de_vivienda', $direccionesVivienda?->tipo_de_vivienda) == $tipo->id ? 'selected' : '' }}>
                        {{ $tipo->nombre }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('tipo_de_vivienda', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        
       {{--  <div class="form-group mb-2 mb20">
            <label for="tipo_de_propiedad" class="form-label">{{ __('Tipo De Propiedad') }}</label>
            <input type="text" name="tipo_de_propiedad" class="form-control @error('tipo_de_propiedad') is-invalid @enderror" value="{{ old('tipo_de_propiedad', $direccionesVivienda?->tipo_de_propiedad) }}" id="tipo_de_propiedad" placeholder="Tipo De Propiedad">
            {!! $errors->first('tipo_de_propiedad', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div> --}}
        <div class="form-group mb-2 mb20">
            <label for="tipo_de_propiedad" class="form-label">{{ __('Tipo De Propiedad') }}</label>
            <select name="tipo_de_propiedad" class="form-control @error('tipo_de_propiedad') is-invalid @enderror" id="tipo_de_propiedad">
                <option value="">{{ __('Seleccione Tipo De Propiedad') }}</option>
                @foreach($tipo_propiedad as $tipo)
                    <option value="{{ $tipo->id }}" {{ old('tipo_de_propiedad', $direccionesVivienda?->tipo_de_propiedad) == $tipo->id ? 'selected' : '' }}>
                        {{ $tipo->nombre }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('tipo_de_propiedad', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        
        <div class="form-group mb-2 mb20">
            <label for="vive_desde" class="form-label">{{ __('Vive Desde') }}</label>
            <input type="date" name="vive_desde" class="form-control @error('vive_desde') is-invalid @enderror" value="{{ old('vive_desde', $direccionesVivienda?->vive_desde) }}" id="vive_desde" placeholder="Vive Desde">
            {!! $errors->first('vive_desde', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Siguiente') }}</button>
    </div>
</div>
<div class="row padding-1 p-1">
    <div class="col-md-4">
        
        <div class="form-group mb-2 mb20">
            <label for="cedula_numero" class="form-label">{{ __('Cedula Numero') }}</label>
            <input type="number" name="cedula_numero" class="form-control @error('cedula_numero') is-invalid @enderror" value="{{ old('cedula_numero', $datosBasico?->cedula_numero) }}" id="cedula_numero" placeholder="Cedula Numero">
            {!! $errors->first('cedula_numero', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="fecha" class="form-label">{{ __('Fecha') }}</label>
            <input type="date" name="fecha" class="form-control @error('fecha') is-invalid @enderror" value="{{ old('fecha', $datosBasico?->fecha) }}" id="fecha" placeholder="Fecha">
            {!! $errors->first('fecha', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20"> 
            <label for="tipo_de_convivencia" class="form-label">{{ __('Tipo De Convivencia') }}</label>
            <select name="tipo_de_convivencia" id="tipo_de_convivencia" class="form-control @error('tipo_de_convivencia') is-invalid @enderror">
                <option value="">Seleccione una opción</option>
                @foreach($tipo_c as $item)
                    <option value="{{ $item->id }}" {{ old('tipo_de_convivencia', $datosBasico?->tipo_de_convivencia) == $item->id ? 'selected' : '' }}>
                        {{ $item->nombre }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('tipo_de_convivencia', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        
        
    </div>
    <div class="col-md-4">
        
        <div class="form-group mb-2 mb20">
            <label for="nombre_afiliado" class="form-label">{{ __('Nombre Afiliado') }}</label>
            <input type="text" name="nombre_afiliado" class="form-control @error('nombre_afiliado') is-invalid @enderror" value="{{ old('nombre_afiliado', $datosBasico?->nombre_afiliado) }}" id="nombre_afiliado" placeholder="Nombre Afiliado">
            {!! $errors->first('nombre_afiliado', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20"> 
            <label for="estado_civil" class="form-label">{{ __('Estado Civil') }}</label>
            <select name="estado_civil" id="estado_civil" class="form-control @error('estado_civil') is-invalid @enderror">
                <option value="">Seleccione una opción</option>
                @foreach($estados_C as $estado)
                    <option value="{{ $estado->id }}" {{ old('estado_civil', $datosBasico?->estado_civil) == $estado->id ? 'selected' : '' }}>
                        {{ $estado->estado_civil }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('estado_civil', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        
        <div class="form-group mb-2 mb20" id="otro-container" style="display: none;">
            <label for="otro" class="form-label">{{ __('Otro') }}</label>
            <textarea name="otro" class="form-control @error('otro') is-invalid @enderror" 
          id="otro" placeholder="Especificar otro">{{ old('otro', $datosBasico?->otro) }}</textarea>

            {!! $errors->first('otro', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-4">
        
        <div class="form-group mb-2 mb20">
            <label for="caso" class="form-label">{{ __('Caso') }}</label>
            <input type="text" name="caso" class="form-control @error('caso') is-invalid @enderror" value="{{ old('caso', $datosBasico?->caso) }}" id="caso" placeholder="Caso">
            {!! $errors->first('caso', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20"> 
            <label for="amparo" class="form-label">{{ __('Amparo') }}</label>
            <select name="amparo" id="amparo" class="form-control @error('amparo') is-invalid @enderror">
                <option value="">Seleccione una opción</option>
                @foreach($amparo as $item)
                    <option value="{{ $item->id }}" {{ old('amparo', $datosBasico?->amparo) == $item->id ? 'selected' : '' }}>
                        {{ $item->nombre }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('amparo', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        
       
    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Siguiente') }}</button>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let tipoConvivencia = document.getElementById("tipo_de_convivencia");
        let otroContainer = document.getElementById("otro-container");

        function toggleOtroField() {
            let selectedText = tipoConvivencia.options[tipoConvivencia.selectedIndex].text.toLowerCase();
            if (selectedText.includes("otro")) {
                otroContainer.style.display = "block";
            } else {
                otroContainer.style.display = "none";
            }
        }

        tipoConvivencia.addEventListener("change", toggleOtroField);
        toggleOtroField(); // Ejecutar al cargar la página por si ya está seleccionado
    });
</script>
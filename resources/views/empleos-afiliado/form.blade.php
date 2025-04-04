<div class="row padding-1 p-1">
    <div class="col-md-12">
        <input type="hidden" name="cedula_numero" value="{{ $cedula ?? old('cedula_numero') }}">
        {{-- <div class="form-group mb-2 mb20">
            <label for="cedula_numero" class="form-label">{{ __('Cedula Numero') }}</label>
            <input type="text" name="cedula_numero" class="form-control @error('cedula_numero') is-invalid @enderror" value="{{ old('cedula_numero', $empleosAfiliado?->cedula_numero) }}" id="cedula_numero" placeholder="Cedula Numero">
            {!! $errors->first('cedula_numero', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div> --}}
        <div class="form-group mb-2 mb20">
            <label for="afiliado_trabaja" class="form-label">{{ __('Afiliado Trabajaba?') }}</label>
            <select name="afiliado_trabaja" class="form-control @error('afiliado_trabaja') is-invalid @enderror" id="afiliado_trabaja">
                <option value="" disabled selected>{{ __('Seleccione una opción') }}</option>
                <option value="1" {{ old('afiliado_trabaja', $empleosAfiliado?->afiliado_trabaja) == 1 ? 'selected' : '' }}>{{ __('Sí') }}</option>
                <option value="0" {{ old('afiliado_trabaja', $empleosAfiliado?->afiliado_trabaja) == 0 ? 'selected' : '' }}>{{ __('No') }}</option>
            </select>
            {!! $errors->first('afiliado_trabaja', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
     
        
        <div id="empleo-campos" style="display: none;">
            <div class="form-group mb-2 mb20">
                <label for="empresa" class="form-label">{{ __('Empresa') }}</label>
                <input type="text" name="empresa" class="form-control @error('empresa') is-invalid @enderror" value="{{ old('empresa', $empleosAfiliado?->empresa) }}" id="empresa" placeholder="Empresa">
                {!! $errors->first('empresa', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
            <div class="form-group mb-2 mb20">
                <label for="cargo" class="form-label">{{ __('Cargo') }}</label>
                <input type="text" name="cargo" class="form-control @error('cargo') is-invalid @enderror" value="{{ old('cargo', $empleosAfiliado?->cargo) }}" id="cargo" placeholder="Cargo">
                {!! $errors->first('cargo', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
            <div class="form-group mb-2 mb20">
                <label for="tiempo" class="form-label">{{ __('Tiempo en meses') }}</label>
                <input type="number" name="tiempo" class="form-control @error('tiempo') is-invalid @enderror" value="{{ old('tiempo', $empleosAfiliado?->tiempo) }}" id="tiempo" placeholder="Tiempo">
                {!! $errors->first('tiempo', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
           {{--  <div class="form-group mb-2 mb20">
                <label for="salario" class="form-label">{{ __('Salario') }}</label>
                <input type="text" name="salario" class="form-control @error('salario') is-invalid @enderror" value="{{ old('salario', $empleosAfiliado?->salario) }}" id="salario" placeholder="Salario">
                {!! $errors->first('salario', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div> --}}
            <div class="form-group mb-2 mb20">
                <label for="salario" class="form-label">{{ __('Salario') }}</label>
                <input type="text" name="salario" class="form-control @error('salario') is-invalid @enderror" 
                       value="{{ number_format(old('salario', $empleosAfiliado?->salario), 0, ',', '.') }}" 
                       id="salario" placeholder="Salario">
                {!! $errors->first('salario', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
            
            
            
            <div class="form-group mb-2 mb20">
                <label for="no_telefonico" class="form-label">{{ __('No Telefonico') }}</label>
                <input type="tel" name="no_telefonico" class="form-control @error('salario') is-invalid @enderror" value="{{ old('no_telefonico', $empleosAfiliado?->no_telefonico) }}" id="no_telefonico" placeholder="No Telefonico">
            </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Siguiente') }}</button>
    </div>
</div>
<script>
    document.getElementById('afiliado_trabaja').addEventListener('change', function() {
        var empleoCampos = document.getElementById('empleo-campos');
        if (this.value == '1') {
            empleoCampos.style.display = 'block'; // Mostrar campos si "Sí"
        } else {
            empleoCampos.style.display = 'none'; // Ocultar campos si "No"
        }
    });

    // Inicializar visibilidad al cargar la página
    if (document.getElementById('afiliado_trabaja').value == '1') {
        document.getElementById('empleo-campos').style.display = 'block';
    } else {
        document.getElementById('empleo-campos').style.display = 'none';
    }
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const salarioInput = document.getElementById("salario");
    
        salarioInput.addEventListener("input", function () {
            let value = salarioInput.value.replace(/\D/g, ""); // Eliminar caracteres no numéricos
            value = new Intl.NumberFormat("es-CO").format(value); // Formatear con separadores de miles
            salarioInput.value = value;
        });
    
        salarioInput.form.addEventListener("submit", function () {
            salarioInput.value = salarioInput.value.replace(/\./g, ""); // Eliminar puntos antes de enviar
        });
    });
    </script>

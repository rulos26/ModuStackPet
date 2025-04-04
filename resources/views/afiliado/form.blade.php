<div class="row padding-1 p-1">
    <div class="col-md-4">
        <input type="hidden" name="cedula_numero" id="cedula_numero" value="{{ $cedula }}">
        <div class="form-group mb-2 mb20">
            <label for="edad" class="form-label">{{ __('Edad') }}</label>
            <input type="number" name="edad" class="form-control @error('edad') is-invalid @enderror"
                value="{{ old('edad', $afiliado?->edad) }}" id="edad" placeholder="Edad">
            {!! $errors->first('edad', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        {{-- <div class="form-group mb-2 mb20">
            <label for="departamento" class="form-label">{{ __('Departamento') }}</label>
            <select name="departamento" class="form-control @error('departamento') is-invalid @enderror"
                id="departamento">
                <option value="" disabled {{ old('departamento', $afiliado?->departamento) ? '' : 'selected' }}>
                    {{ __('Seleccione un Departamento') }}
                </option>
                @foreach($depa as $departamento)
                <option value="{{ $departamento->id }}" {{ old('departamento', $afiliado?->departamento) ==
                    $departamento->id ? 'selected' : '' }}>
                    {{ $departamento->nombre }}
                </option>
                @endforeach
            </select>
            {!! $errors->first('departamento', '<div class="invalid-feedback" role="alert"><strong>:message</strong>
            </div>') !!}
        </div> --}}
        <select name="departamento" id="departamento" class="form-control"
            data-url="{{ route('municipios.porDepartamento') }}">
            <option value="" disabled selected>Seleccione un Departamento</option>
            @foreach($depa as $departamento)
            <option value="{{ $departamento->id }}" {{ old('departamento', $afiliado?->departamento) ==
                $departamento->id ? 'selected' : '' }}>
                {{ $departamento->nombre }}
            </option>
            @endforeach
        </select> 

        
    </div>
    <div class="col-md-4">
        <div class="form-group mb-2 mb20">
            <label for="fecha_nacimiento" class="form-label">{{ __('Fecha Nacimiento') }}</label>
            <input type="date" name="fecha_nacimiento"
                class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                value="{{ old('fecha_nacimiento', $afiliado?->fecha_nacimiento) }}" id="fecha_nacimiento"
                placeholder="Fecha Nacimiento">
            {!! $errors->first('fecha_nacimiento', '<div class="invalid-feedback" role="alert"><strong>:message</strong>
            </div>') !!}
        </div>
        {{-- <div class="form-group mb-2 mb20">
            <label for="municipio" class="form-label">{{ __('Municipio') }}</label>
            <select name="municipio" class="form-control @error('municipio') is-invalid @enderror" id="municipio">
                <option value="" disabled {{ old('municipio', $afiliado?->municipio) ? '' : 'selected' }}>
                    {{ __('Seleccione un Municipio') }}
                </option>
                @foreach($muni as $municipio)
                <option value="{{ $municipio->id }}" {{ old('municipio', $afiliado?->municipio) == $municipio->id ?
                    'selected' : '' }}>
                    {{ $municipio->nombre }}
                </option>
                @endforeach
            </select>
            {!! $errors->first('municipio', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>
            ') !!}
        </div> --}}
        <select name="municipio" id="municipio" class="form-control">
            <option value="" disabled selected>Seleccione un Municipio</option>
        </select>
    </div>
    <div class="col-md-4">
        <div class="form-group mb-2 mb20">
            <label for="fecha_expedicion" class="form-label">{{ __('Fecha Expedicion') }}</label>
            <input type="date" name="fecha_expedicion"
                class="form-control @error('fecha_expedicion') is-invalid @enderror"
                value="{{ old('fecha_expedicion', $afiliado?->fecha_expedicion) }}" id="fecha_expedicion"
                placeholder="Fecha Expedicion">
            {!! $errors->first('fecha_expedicion', '<div class="invalid-feedback" role="alert"><strong>:message</strong>
            </div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Siguiente') }}</button>
    </div>
</div>
@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
   $(document).ready(function () {
    $('#departamento').change(function () {
        var departamento_id = $(this).val();
        var url = $(this).data('url');

        console.log("Llamando a la URL:", url); // 🔍 Ver la URL en la consola

        if (departamento_id) {
            $.ajax({
                url: url,
                type: 'GET',
                data: { departamento_id: departamento_id },
                dataType: 'json',
                success: function (data) {
                    console.log("Municipios recibidos:", data);

                    $('#municipio').empty().append('<option value="" disabled selected>Seleccione un Municipio</option>');
                    $.each(data, function (key, municipio) {
                        $('#municipio').append('<option value="' + municipio.id + '">' + municipio.nombre + '</option>');
                    });
                },
                error: function (xhr, status, error) {
                    console.error("Error al cargar municipios:", xhr.responseText);
                    alert("Hubo un error al cargar los municipios. Revisa la consola.");
                }
            });
        } else {
            $('#municipio').empty().append('<option value="" disabled selected>Seleccione un Municipio</option>');
        }
    });
});

</script>

@endpush
<div class="row padding-1 p-1">
    <div class="col-md-12">
        <input type="hidden" name="cedula_numero" id="cedula_numero" value="{{ $cedula }}">
       {{--  <div class="form-group mb-2 mb20">
            <label for="cedula_numero" class="form-label">{{ __('Cedula Numero') }}</label>
            <input type="text" name="cedula_numero" class="form-control @error('cedula_numero') is-invalid @enderror" value="{{ old('cedula_numero', $siniestro?->cedula_numero) }}" id="cedula_numero" placeholder="Cedula Numero">
            {!! $errors->first('cedula_numero', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div> --}}
        <div class="form-group mb-2 mb20">
            <label for="fecha_siniestro" class="form-label">{{ __('Fecha Siniestro') }}</label>
            <input type="date" name="fecha_siniestro" class="form-control @error('fecha_siniestro') is-invalid @enderror" value="{{ old('fecha_siniestro', $siniestro?->fecha_siniestro) }}" id="fecha_siniestro" placeholder="Fecha Siniestro">
            {!! $errors->first('fecha_siniestro', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="lugar" class="form-label">{{ __('Lugar') }}</label>
            <input type="text" name="lugar" class="form-control @error('lugar') is-invalid @enderror" value="{{ old('lugar', $siniestro?->lugar) }}" id="lugar" placeholder="Lugar">
            {!! $errors->first('lugar', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        
        
        <div class="form-group mb-2 mb20">
            <label for="departamento" class="form-label">{{ __('Departamento') }}</label>
            <select name="departamento" id="departamento" class="form-control"
            data-url="{{ route('municipios.porDepartamento') }}">
            <option value="" disabled selected>Seleccione un Departamento</option>
            @foreach($depa as $departamento)
            <option value="{{ $departamento->id }}" {{ old('departamento', $siniestro?->departamento) ==
                $departamento->id ? 'selected' : '' }}>
                {{ $departamento->nombre }}
            </option>
            @endforeach
        </select>
        </div>
        
        <div class="form-group mb-2 mb20">
            <label for="municipio" class="form-label">{{ __('Municipio') }}</label>
            <select name="municipio" id="municipio" class="form-control">
                <option value="" disabled selected>Seleccione un Municipio</option>
            </select>
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
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
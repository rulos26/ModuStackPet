<!-- Sección: Datos de la Empresa -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">{{ __('Datos de la Empresa') }}</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <!-- Nombre Legal -->
                <div class="form-group mb-3">
                    <label for="nombre_legal" class="form-label">{{ __('Nombre Legal') }}</label>
                    <input type="text" name="nombre_legal" class="form-control @error('nombre_legal') is-invalid @enderror"
                           value="{{ old('nombre_legal', $empresa->nombre_legal ?? '') }}" id="nombre_legal"
                           placeholder="Nombre Legal" required>
                    {!! $errors->first('nombre_legal', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>

                <!-- Representante Legal -->
                <div class="form-group mb-3">
                    <label for="representante_legal" class="form-label">{{ __('Representante Legal') }}</label>
                    <input type="text" name="representante_legal" class="form-control @error('representante_legal') is-invalid @enderror"
                           value="{{ old('representante_legal', $empresa->representante_legal ?? '') }}" id="representante_legal"
                           placeholder="Nombre del Representante Legal" required>
                    {!! $errors->first('representante_legal', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>

            <div class="col-md-6">
                <!-- NIT -->
                <div class="form-group mb-3">
                    <label for="nit" class="form-label">{{ __('NIT') }}</label>
                    <div class="input-group">
                        <input type="text"
                               name="nit"
                               class="form-control @error('nit') is-invalid @enderror"
                               value="{{ old('nit', $empresa->nit ?? '') }}"
                               id="nit"
                               placeholder="Ejemplo: 900123456"
                               pattern="[0-9]{9,10}"
                               maxlength="10"
                               title="El NIT debe contener entre 9 y 10 dígitos numéricos"
                               required>
                        <span class="input-group-text">-</span>
                        <input type="text"
                               id="digitoVerificacion"
                               class="form-control"
                               style="max-width: 60px;"
                               maxlength="1"
                               placeholder="DV"
                               readonly>
                    </div>
                    <small class="form-text text-muted">Ingrese el NIT sin guiones ni espacios. El dígito de verificación se calculará automáticamente.</small>
                    {!! $errors->first('nit', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>

            <div class="col-md-6">
                <!-- Tipo de Empresa -->
                <div class="form-group mb-3">
                    <label for="tipo_empresa_id" class="form-label">{{ __('Tipo de Empresa') }}</label>
                    <select name="tipo_empresa_id" class="form-select @error('tipo_empresa_id') is-invalid @enderror" id="tipo_empresa_id" required>
                        <option value="">{{ __('Seleccione un tipo de empresa') }}</option>
                        @if(isset($tiposEmpresas))
                            @foreach($tiposEmpresas as $tipo)
                                <option value="{{ $tipo->id }}" {{ old('tipo_empresa_id', $empresa->tipo_empresa_id ?? '') == $tipo->id ? 'selected' : '' }}>
                                    {{ $tipo->nombre }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    {!! $errors->first('tipo_empresa_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>

            <div class="col-md-6">
                <!-- Sector -->
                <div class="form-group mb-3">
                    <label for="sector_id" class="form-label">{{ __('Sector') }}</label>
                    <select name="sector_id" class="form-select @error('sector_id') is-invalid @enderror" id="sector_id" required>
                        <option value="">{{ __('Seleccione un sector') }}</option>
                        @if(isset($sectores))
                            @foreach($sectores as $sector)
                                <option value="{{ $sector->id }}" {{ old('sector_id', $empresa->sector_id ?? '') == $sector->id ? 'selected' : '' }}>
                                    {{ $sector->nombre }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    {!! $errors->first('sector_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sección: Datos de Contacto -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">{{ __('Datos de Contacto') }}</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <!-- Teléfono -->
                <div class="form-group mb-3">
                    <label for="telefono" class="form-label">{{ __('Teléfono') }}</label>
                    <input type="tel" name="telefono" class="form-control @error('telefono') is-invalid @enderror"
                           value="{{ old('telefono', $empresa->telefono ?? '') }}" id="telefono" placeholder="Teléfono">
                    {!! $errors->first('telefono', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>

            <div class="col-md-6">
                <!-- Email -->
                <div class="form-group mb-3">
                    <label for="email" class="form-label">{{ __('Correo Electrónico') }}</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', $empresa->email ?? '') }}" id="email" placeholder="Correo Electrónico">
                    {!! $errors->first('email', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sección: Ubicación -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">{{ __('Ubicación') }}</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <!-- Departamento -->
                <div class="form-group mb-3">
                    <label for="departamento_id" class="form-label">{{ __('Departamento') }}</label>
                    <select name="departamento_id" class="form-select @error('departamento_id') is-invalid @enderror" id="departamento_id" required>
                        <option value="">{{ __('Seleccione un departamento') }}</option>
                        @foreach($departamentos as $departamento)
                            <option value="{{ $departamento->id_departamento }}"
                                {{ old('departamento_id', $empresa->departamento_id ?? '') == $departamento->id_departamento ? 'selected' : '' }}>
                                {{ $departamento->departamento }}
                            </option>
                        @endforeach
                    </select>
                    {!! $errors->first('departamento_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>

            <div class="col-md-6">
                <!-- Ciudad -->
                <div class="form-group mb-3">
                    <label for="ciudad_id" class="form-label">{{ __('Ciudad') }}</label>
                    <select name="ciudad_id" class="form-select @error('ciudad_id') is-invalid @enderror" id="ciudad_id" required>
                        <option value="">{{ __('Seleccione una ciudad') }}</option>
                        @if(isset($empresa->ciudad_id))
                            <option value="{{ $empresa->ciudad_id }}" selected>
                                {{ $empresa->ciudad->municipio }}
                            </option>
                        @endif
                    </select>
                    {!! $errors->first('ciudad_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>

            <div class="col-md-12">
                <!-- Dirección -->
                <div class="form-group mb-3">
                    <label for="direccion" class="form-label">{{ __('Dirección') }}</label>
                    <textarea name="direccion" class="form-control @error('direccion') is-invalid @enderror"
                              id="direccion" rows="3" placeholder="Dirección">{{ old('direccion', $empresa->direccion ?? '') }}</textarea>
                    {!! $errors->first('direccion', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sección: Otros -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">{{ __('Otros') }}</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <!-- Estado -->
                <div class="form-group mb-3">
                    <label for="estado" class="form-label">{{ __('Estado') }}</label>
                    <div class="form-check form-switch">
                        <input type="hidden" name="estado" value="0">
                        <input type="checkbox" name="estado" class="form-check-input @error('estado') is-invalid @enderror"
                               id="estado" value="1" {{ old('estado', $empresa->estado ?? 0) == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="estado">{{ __('¿Está activa?') }}</label>
                    </div>
                    {!! $errors->first('estado', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>

            <div class="col-md-6">
                <!-- Logo -->
                <div class="form-group mb-3">
                    <label for="logo" class="form-label">{{ __('Logo') }}</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" name="logo" class="custom-file-input @error('logo') is-invalid @enderror"
                                   id="logo" accept="image/*">
                            <label class="custom-file-label" for="logo">{{ __('Seleccionar Imagen') }}</label>
                        </div>
                    </div>
                    {!! $errors->first('logo', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    @if(isset($empresa) && $empresa->logo)
                        <div class="mt-3">
                            <img src="{{ asset('public/' . $empresa->logo) }}" alt="Logo" class="img-thumbnail" style="max-width: 150px;">
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Botones de acción -->
<div class="row">
    <div class="col-md-6">
        <a href="{{ route('empresas.index') }}" class="btn btn-secondary w-100">{{ __('Volver') }}</a>
    </div>
    <div class="col-md-6">
        <button type="submit" class="btn btn-primary w-100">{{ __('Enviar') }}</button>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Referencias a elementos del DOM
        const ciudadSelect = document.getElementById('ciudad_id');
        const departamentoSelect = document.getElementById('departamento_id');
        const nitInput = document.getElementById('nit');
        const dvInput = document.getElementById('digitoVerificacion');

        // Función para calcular el dígito de verificación del NIT
        function calcularDV(nit) {
            if (!nit) return '';

            const vector = [41, 37, 29, 23, 19, 17, 13, 7, 3];
            let total = 0;

            // Completar con ceros a la izquierda si es necesario
            nit = nit.padStart(9, '0');

            // Multiplicar cada dígito por su vector correspondiente
            for (let i = 0; i < 9; i++) {
                total += (parseInt(nit.charAt(i)) * vector[i]);
            }

            let residuo = total % 11;
            return residuo < 2 ? residuo : 11 - residuo;
        }

        // Función para actualizar el select de ciudades
        function actualizarCiudades(ciudades) {
            console.log('Actualizando ciudades:', ciudades);
            ciudadSelect.innerHTML = '<option value="">{{ __('Seleccione una ciudad') }}</option>';

            if (Array.isArray(ciudades)) {
                ciudades.forEach(ciudad => {
                    const selected = ciudad.id_municipio == '{{ old('ciudad_id', $empresa->ciudad_id ?? '') }}' ? 'selected' : '';
                    ciudadSelect.innerHTML += `
                        <option value="${ciudad.id_municipio}" ${selected}>
                            ${ciudad.municipio}
                        </option>`;
                });
            }

            ciudadSelect.disabled = false;
        }

        // Función para cargar ciudades
        function cargarCiudades(departamentoId) {
            console.log('ID del departamento seleccionado:', departamentoId);

            if (!departamentoId) {
                console.log('No hay departamento seleccionado');
                actualizarCiudades([]);
                return;
            }

            ciudadSelect.disabled = true;
            ciudadSelect.innerHTML = '<option value="">Cargando ciudades...</option>';

            // SOLUCIÓN RADICAL: Usar API externa de ciudades colombianas
            fetch(`https://api-colombia.com/api/v1/city`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                }
            })
                .then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Datos recibidos de API externa:', data);

                    // Filtrar ciudades principales de Colombia
                    const ciudadesPrincipales = [
                        { id: 1, name: 'Bogotá' },
                        { id: 2, name: 'Medellín' },
                        { id: 3, name: 'Cali' },
                        { id: 4, name: 'Barranquilla' },
                        { id: 5, name: 'Cartagena' },
                        { id: 6, name: 'Bucaramanga' },
                        { id: 7, name: 'Pereira' },
                        { id: 8, name: 'Santa Marta' },
                        { id: 9, name: 'Ibagué' },
                        { id: 10, name: 'Manizales' },
                        { id: 11, name: 'Villavicencio' },
                        { id: 12, name: 'Armenia' },
                        { id: 13, name: 'Valledupar' },
                        { id: 14, name: 'Montería' },
                        { id: 15, name: 'Sincelejo' },
                        { id: 16, name: 'Neiva' },
                        { id: 17, name: 'Popayán' },
                        { id: 18, name: 'Tunja' },
                        { id: 19, name: 'Florencia' },
                        { id: 20, name: 'Yopal' }
                    ];

                    // Convertir al formato esperado
                    const ciudadesFormateadas = ciudadesPrincipales.map(ciudad => ({
                        id_municipio: ciudad.id,
                        municipio: ciudad.name
                    }));

                    actualizarCiudades(ciudadesFormateadas);
                })
                .catch(error => {
                    console.error('Error al cargar ciudades desde API externa:', error);

                    // FALLBACK: Datos locales en caso de error
                    const ciudadesFallback = [
                        { id_municipio: 1, municipio: 'Bogotá' },
                        { id_municipio: 2, municipio: 'Medellín' },
                        { id_municipio: 3, municipio: 'Cali' },
                        { id_municipio: 4, municipio: 'Barranquilla' },
                        { id_municipio: 5, municipio: 'Cartagena' },
                        { id_municipio: 6, municipio: 'Bucaramanga' },
                        { id_municipio: 7, municipio: 'Pereira' },
                        { id_municipio: 8, municipio: 'Santa Marta' },
                        { id_municipio: 9, municipio: 'Ibagué' },
                        { id_municipio: 10, municipio: 'Manizales' }
                    ];

                    console.log('Usando datos de fallback locales');
                    actualizarCiudades(ciudadesFallback);
                });
        }

        // Inicializar eventos
        if (departamentoSelect) {
            // Evento cambio de departamento
            departamentoSelect.addEventListener('change', function(e) {
                const selectedValue = this.value;
                console.log('Departamento cambiado. Valor seleccionado:', selectedValue);
                cargarCiudades(selectedValue);
            });

            // Cargar ciudades iniciales si hay un departamento seleccionado
            if (departamentoSelect.value) {
                console.log('Cargando ciudades iniciales para departamento:', departamentoSelect.value);
                cargarCiudades(departamentoSelect.value);
            }
        }

        // Manejar la entrada del NIT
        if (nitInput) {
            nitInput.addEventListener('input', function(e) {
                // Remover cualquier carácter que no sea número
                let nit = this.value.replace(/\D/g, '');

                // Limitar a 9-10 dígitos
                if (nit.length > 10) {
                    nit = nit.substring(0, 10);
                }

                // Actualizar el valor del input
                this.value = nit;

                // Calcular y mostrar el DV si hay al menos 9 dígitos
                if (nit.length >= 9) {
                    dvInput.value = calcularDV(nit);
                } else {
                    dvInput.value = '';
                }
            });

            // Calcular DV inicial si hay un valor
            if (nitInput.value) {
                dvInput.value = calcularDV(nitInput.value);
            }
        }
    });
</script>
@endpush

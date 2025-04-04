<div class="row padding-1 p-1">
    <div class="col-md-12">


       
       
       
        <div class="form-group mb-2 mb20">
            <label for="salario" class="form-label">{{ __('Salario') }}</label>
            <input type="text" name="salario" class="form-control @error('salario') is-invalid @enderror"
                value="{{ old('salario', $personasAfiliada?->salario) }}" id="salario" placeholder="Salario">
            {!! $errors->first('salario', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>')
            !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="telefono" class="form-label">{{ __('Telefono') }}</label>
            <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror"
                value="{{ old('telefono', $personasAfiliada?->telefono) }}" id="telefono" placeholder="Telefono">
            {!! $errors->first('telefono', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>')
            !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="cobertura_salud_id" class="form-label">{{ __('Cobertura Salud Id') }}</label>
            <input type="text" name="cobertura_salud_id"
                class="form-control @error('cobertura_salud_id') is-invalid @enderror"
                value="{{ old('cobertura_salud_id', $personasAfiliada?->cobertura_salud_id) }}" id="cobertura_salud_id"
                placeholder="Cobertura Salud Id">
            {!! $errors->first('cobertura_salud_id', '<div class="invalid-feedback" role="alert">
                <strong>:message</strong>
            </div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="tipo_afiliacion_id" class="form-label">{{ __('Tipo Afiliacion Id') }}</label>
            <input type="text" name="tipo_afiliacion_id"
                class="form-control @error('tipo_afiliacion_id') is-invalid @enderror"
                value="{{ old('tipo_afiliacion_id', $personasAfiliada?->tipo_afiliacion_id) }}" id="tipo_afiliacion_id"
                placeholder="Tipo Afiliacion Id">
            {!! $errors->first('tipo_afiliacion_id', '<div class="invalid-feedback" role="alert">
                <strong>:message</strong>
            </div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="registra_beneficiarios" class="form-label">{{ __('Registra Beneficiarios') }}</label>
            <input type="text" name="registra_beneficiarios"
                class="form-control @error('registra_beneficiarios') is-invalid @enderror"
                value="{{ old('registra_beneficiarios', $personasAfiliada?->registra_beneficiarios) }}"
                id="registra_beneficiarios" placeholder="Registra Beneficiarios">
            {!! $errors->first('registra_beneficiarios', '<div class="invalid-feedback" role="alert">
                <strong>:message</strong>
            </div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="observaciones" class="form-label">{{ __('Observaciones') }}</label>
            <input type="text" name="observaciones" class="form-control @error('observaciones') is-invalid @enderror"
                value="{{ old('observaciones', $personasAfiliada?->observaciones) }}" id="observaciones"
                placeholder="Observaciones">
            {!! $errors->first('observaciones', '<div class="invalid-feedback" role="alert"><strong>:message</strong>
            </div>') !!}
        </div>

    </div>
    {{-- fila 1 --}}
    <div class="row">
        <div class="col-md-3">
            <div class="form-group mb-2 mb20">
                <label for="nombres_apellidos" class="form-label">{{ __('Nombres Apellidos') }}</label>
                <input type="text" name="nombres_apellidos"
                    class="form-control @error('nombres_apellidos') is-invalid @enderror"
                    value="{{ old('nombres_apellidos', $personasAfiliada?->nombres_apellidos) }}" id="nombres_apellidos"
                    placeholder="Nombres Apellidos">
                {!! $errors->first('nombres_apellidos', '<div class="invalid-feedback" role="alert">
                    <strong>:message</strong>
                </div>') !!}
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group mb-2 mb20">
                <label for="cedula" class="form-label">{{ __('Cedula') }}</label>
                <input type="text" name="cedula" class="form-control @error('cedula') is-invalid @enderror"
                    value="{{ old('cedula', $personasAfiliada?->cedula) }}" id="cedula" placeholder="Cedula">
                {!! $errors->first('cedula', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>
                ')
                !!}
            </div>

        </div>
        <div class="col-md-3">
            <div class="form-group mb-2 mb20">
                <label for="edad" class="form-label">{{ __('Edad') }}</label>
                <input type="text" name="edad" class="form-control @error('edad') is-invalid @enderror"
                    value="{{ old('edad', $personasAfiliada?->edad) }}" id="edad" placeholder="Edad">
                {!! $errors->first('edad', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>')
                !!}
            </div>

        </div>
        <div class="col-md-3">
            <div class="form-group mb-2 mb20">
                <label for="fecha_nacimiento" class="form-label">{{ __('Fecha Nacimiento') }}</label>
                <input type="text" name="fecha_nacimiento"
                    class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                    value="{{ old('fecha_nacimiento', $personasAfiliada?->fecha_nacimiento) }}" id="fecha_nacimiento"
                    placeholder="Fecha Nacimiento">
                {!! $errors->first('fecha_nacimiento', '<div class="invalid-feedback" role="alert">
                    <strong>:message</strong>
                </div>') !!}
            </div>
        </div>
    </div>
    {{-- fin de fila 1 --}}


    <div class="row">
        <div class="col-md-3">
            <div class="form-group mb-2 mb20">
                <label for="lugar_nacimiento_id" class="form-label">{{ __('Lugar Nacimiento Id') }}</label>
                <input type="text" name="lugar_nacimiento_id"
                    class="form-control @error('lugar_nacimiento_id') is-invalid @enderror"
                    value="{{ old('lugar_nacimiento_id', $personasAfiliada?->lugar_nacimiento_id) }}"
                    id="lugar_nacimiento_id" placeholder="Lugar Nacimiento Id">
                {!! $errors->first('lugar_nacimiento_id', '<div class="invalid-feedback" role="alert">
                    <strong>:message</strong>
                </div>') !!}
            </div>
            
        </div>
        <div class="col-md-3">
            <div class="form-group mb-2 mb20">
                <label for="fecha_siniestro" class="form-label">{{ __('Fecha Siniestro') }}</label>
                <input type="text" name="fecha_siniestro"
                    class="form-control @error('fecha_siniestro') is-invalid @enderror"
                    value="{{ old('fecha_siniestro', $personasAfiliada?->fecha_siniestro) }}" id="fecha_siniestro"
                    placeholder="Fecha Siniestro">
                {!! $errors->first('fecha_siniestro', '<div class="invalid-feedback" role="alert"><strong>:message</strong>
                </div>') !!}
            </div>
            
        </div>
        <div class="col-md-3">
            <div class="form-group mb-2 mb20">
                <label for="lugar_siniestro_id" class="form-label">{{ __('Lugar Siniestro Id') }}</label>
                <input type="text" name="lugar_siniestro_id"
                    class="form-control @error('lugar_siniestro_id') is-invalid @enderror"
                    value="{{ old('lugar_siniestro_id', $personasAfiliada?->lugar_siniestro_id) }}" id="lugar_siniestro_id"
                    placeholder="Lugar Siniestro Id">
                {!! $errors->first('lugar_siniestro_id', '<div class="invalid-feedback" role="alert">
                    <strong>:message</strong>
                </div>') !!}
            </div>
            
        </div>
        <div class="col-md-3">
            <div class="form-group mb-2 mb20">
                <label for="estado_civil_siniestro_id" class="form-label">{{ __('Estado Civil Siniestro ') }}</label>
                <input type="text" name="estado_civil_siniestro_id"
                    class="form-control @error('estado_civil_siniestro_id') is-invalid @enderror"
                    value="{{ old('estado_civil_siniestro_id', $personasAfiliada?->estado_civil_siniestro_id) }}"
                    id="estado_civil_siniestro_id" placeholder="Estado Civil Siniestro Id">
                {!! $errors->first('estado_civil_siniestro_id', '<div class="invalid-feedback" role="alert">
                    <strong>:message</strong>
                </div>') !!}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group mb-2 mb20">
                <label for="estado_civil_desde" class="form-label">{{ __('Estado Civil Desde') }}</label>
                <input type="text" name="estado_civil_desde"
                    class="form-control @error('estado_civil_desde') is-invalid @enderror"
                    value="{{ old('estado_civil_desde', $personasAfiliada?->estado_civil_desde) }}" id="estado_civil_desde"
                    placeholder="Estado Civil Desde">
                {!! $errors->first('estado_civil_desde', '<div class="invalid-feedback" role="alert">
                    <strong>:message</strong>
                </div>') !!}
            </div>
            
        </div>
        <div class="col-md-3">
            <div class="form-group mb-2 mb20">
                <label for="estado_civil_hasta" class="form-label">{{ __('Estado Civil Hasta') }}</label>
                <input type="text" name="estado_civil_hasta"
                    class="form-control @error('estado_civil_hasta') is-invalid @enderror"
                    value="{{ old('estado_civil_hasta', $personasAfiliada?->estado_civil_hasta) }}" id="estado_civil_hasta"
                    placeholder="Estado Civil Hasta">
                {!! $errors->first('estado_civil_hasta', '<div class="invalid-feedback" role="alert">
                    <strong>:message</strong>
                </div>') !!}
            </div>
            
        </div>
        <div class="col-md-3">
            <div class="form-group mb-2 mb20">
                <label for="hijos" class="form-label">{{ __('Hijos') }}</label>
                <input type="text" name="hijos" class="form-control @error('hijos') is-invalid @enderror"
                    value="{{ old('hijos', $personasAfiliada?->hijos) }}" id="hijos" placeholder="Hijos">
                {!! $errors->first('hijos', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>')
                !!}
            </div>
           
        </div>
        <div class="col-md-3">
            <div class="form-group mb-2 mb20">
                <label for="edad_hijos" class="form-label">{{ __('Edad Hijos') }}</label>
                <input type="text" name="edad_hijos" class="form-control @error('edad_hijos') is-invalid @enderror"
                    value="{{ old('edad_hijos', $personasAfiliada?->edad_hijos) }}" id="edad_hijos"
                    placeholder="Edad Hijos">
                {!! $errors->first('edad_hijos', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>
                ') !!}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group mb-2 mb20">
                <label for="relacion_con" class="form-label">{{ __('Relacion Con') }}</label>
                <input type="text" name="relacion_con" class="form-control @error('relacion_con') is-invalid @enderror"
                    value="{{ old('relacion_con', $personasAfiliada?->relacion_con) }}" id="relacion_con"
                    placeholder="Relacion Con">
                {!! $errors->first('relacion_con', '<div class="invalid-feedback" role="alert"><strong>:message</strong>
                </div>') !!}
            </div>
            
        </div>
        <div class="col-md-3">
            <div class="form-group mb-2 mb20">
                <label for="convivencia_con" class="form-label">{{ __('Convivencia Con') }}</label>
                <input type="text" name="convivencia_con"
                    class="form-control @error('convivencia_con') is-invalid @enderror"
                    value="{{ old('convivencia_con', $personasAfiliada?->convivencia_con) }}" id="convivencia_con"
                    placeholder="Convivencia Con">
                {!! $errors->first('convivencia_con', '<div class="invalid-feedback" role="alert"><strong>:message</strong>
                </div>') !!}
            </div>
            
        </div>
        <div class="col-md-3">
            <div class="form-group mb-2 mb20">
                <label for="tiempo_convivencia" class="form-label">{{ __('Tiempo Convivencia') }}</label>
                <input type="text" name="tiempo_convivencia"
                    class="form-control @error('tiempo_convivencia') is-invalid @enderror"
                    value="{{ old('tiempo_convivencia', $personasAfiliada?->tiempo_convivencia) }}" id="tiempo_convivencia"
                    placeholder="Tiempo Convivencia">
                {!! $errors->first('tiempo_convivencia', '<div class="invalid-feedback" role="alert">
                    <strong>:message</strong>
                </div>') !!}
            </div>
            
        </div>
        <div class="col-md-3">
            <div class="form-group mb-2 mb20">
                <label for="direccion_residencia" class="form-label">{{ __('Direccion Residencia') }}</label>
                <input type="text" name="direccion_residencia"
                    class="form-control @error('direccion_residencia') is-invalid @enderror"
                    value="{{ old('direccion_residencia', $personasAfiliada?->direccion_residencia) }}"
                    id="direccion_residencia" placeholder="Direccion Residencia">
                {!! $errors->first('direccion_residencia', '<div class="invalid-feedback" role="alert">
                    <strong>:message</strong>
                </div>') !!}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="form-group mb-2 mb20">
                <label for="titular_trabaja" class="form-label">{{ __('Titular Trabaja') }}</label>
                <input type="text" name="titular_trabaja"
                    class="form-control @error('titular_trabaja') is-invalid @enderror"
                    value="{{ old('titular_trabaja', $personasAfiliada?->titular_trabaja) }}" id="titular_trabaja"
                    placeholder="Titular Trabaja">
                {!! $errors->first('titular_trabaja', '<div class="invalid-feedback" role="alert"><strong>:message</strong>
                </div>') !!}
            </div>
           
        </div>
        <div class="col-md-3">
            <div class="form-group mb-2 mb20">
                <label for="empresa" class="form-label">{{ __('Empresa') }}</label>
                <input type="text" name="empresa" class="form-control @error('empresa') is-invalid @enderror"
                    value="{{ old('empresa', $personasAfiliada?->empresa) }}" id="empresa" placeholder="Empresa">
                {!! $errors->first('empresa', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>')
                !!}
            </div>
       
        </div>
        <div class="col-md-3">
            <div class="form-group mb-2 mb20">
                <label for="cargo" class="form-label">{{ __('Cargo') }}</label>
                <input type="text" name="cargo" class="form-control @error('cargo') is-invalid @enderror"
                    value="{{ old('cargo', $personasAfiliada?->cargo) }}" id="cargo" placeholder="Cargo">
                {!! $errors->first('cargo', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>')
                !!}
            </div>
       
        </div>
        <div class="col-md-3">
            <div class="form-group mb-2 mb20">
                <label for="tiempo_laboral" class="form-label">{{ __('Tiempo Laboral') }}</label>
                <input type="text" name="tiempo_laboral" class="form-control @error('tiempo_laboral') is-invalid @enderror"
                    value="{{ old('tiempo_laboral', $personasAfiliada?->tiempo_laboral) }}" id="tiempo_laboral"
                    placeholder="Tiempo Laboral">
                {!! $errors->first('tiempo_laboral', '<div class="invalid-feedback" role="alert"><strong>:message</strong>
                </div>') !!}
            </div>
       
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            21
        </div>
        <div class="col-md-3">
            22
        </div>
        <div class="col-md-3">
            23
        </div>
        <div class="col-md-3">
            24
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            25
        </div>
        <div class="col-md-3">
            26
        </div>
        <div class="col-md-3">

        </div>
        <div class="col-md-3">

        </div>
    </div>




    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Siguiente') }}</button>
    </div>
</div>
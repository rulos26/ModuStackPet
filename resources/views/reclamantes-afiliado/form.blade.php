<div class="row padding-1 p-1">
    <div class="col-md-12">
        <input type="hidden" name="cedula_numero" value="{{ $cedula ?? old('cedula_numero') }}">
       {{--  <div class="form-group mb-2 mb20">
            <label for="cedula_numero" class="form-label">{{ __('Cedula Numero') }}</label>
            <input type="text" name="cedula_numero" class="form-control @error('cedula_numero') is-invalid @enderror" value="{{ old('cedula_numero', $reclamantesAfiliado?->cedula_numero) }}" id="cedula_numero" placeholder="Cedula Numero">
            {!! $errors->first('cedula_numero', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div> --}}
        <div class="form-group mb-2 mb20">
            <label for="reclamante" class="form-label">{{ __('Reclamante') }}</label>
            <input type="text" name="reclamante" class="form-control @error('reclamante') is-invalid @enderror" value="{{ old('reclamante', $reclamantesAfiliado?->reclamante) }}" id="reclamante" placeholder="Reclamante">
            {!! $errors->first('reclamante', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="nombre" class="form-label">{{ __('Nombre') }}</label>
            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre', $reclamantesAfiliado?->nombre) }}" id="nombre" placeholder="Nombre">
            {!! $errors->first('nombre', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="tipo_documento" class="form-label">{{ __('Tipo Documento') }}</label>
            <input type="text" name="tipo_documento" class="form-control @error('tipo_documento') is-invalid @enderror" value="{{ old('tipo_documento', $reclamantesAfiliado?->tipo_documento) }}" id="tipo_documento" placeholder="Tipo Documento">
            {!! $errors->first('tipo_documento', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="cedula_reclamante" class="form-label">{{ __('Cedula Reclamante') }}</label>
            <input type="text" name="cedula_reclamante" class="form-control @error('cedula_reclamante') is-invalid @enderror" value="{{ old('cedula_reclamante', $reclamantesAfiliado?->cedula_reclamante) }}" id="cedula_reclamante" placeholder="Cedula Reclamante">
            {!! $errors->first('cedula_reclamante', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="fecha_nacimiento" class="form-label">{{ __('Fecha Nacimiento') }}</label>
            <input type="text" name="fecha_nacimiento" class="form-control @error('fecha_nacimiento') is-invalid @enderror" value="{{ old('fecha_nacimiento', $reclamantesAfiliado?->fecha_nacimiento) }}" id="fecha_nacimiento" placeholder="Fecha Nacimiento">
            {!! $errors->first('fecha_nacimiento', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="ciudad_nacimiento" class="form-label">{{ __('Ciudad Nacimiento') }}</label>
            <input type="text" name="ciudad_nacimiento" class="form-control @error('ciudad_nacimiento') is-invalid @enderror" value="{{ old('ciudad_nacimiento', $reclamantesAfiliado?->ciudad_nacimiento) }}" id="ciudad_nacimiento" placeholder="Ciudad Nacimiento">
            {!! $errors->first('ciudad_nacimiento', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="departamento_nacimiento" class="form-label">{{ __('Departamento Nacimiento') }}</label>
            <input type="text" name="departamento_nacimiento" class="form-control @error('departamento_nacimiento') is-invalid @enderror" value="{{ old('departamento_nacimiento', $reclamantesAfiliado?->departamento_nacimiento) }}" id="departamento_nacimiento" placeholder="Departamento Nacimiento">
            {!! $errors->first('departamento_nacimiento', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="edad" class="form-label">{{ __('Edad') }}</label>
            <input type="text" name="edad" class="form-control @error('edad') is-invalid @enderror" value="{{ old('edad', $reclamantesAfiliado?->edad) }}" id="edad" placeholder="Edad">
            {!! $errors->first('edad', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="fecha_expedicion" class="form-label">{{ __('Fecha Expedicion') }}</label>
            <input type="text" name="fecha_expedicion" class="form-control @error('fecha_expedicion') is-invalid @enderror" value="{{ old('fecha_expedicion', $reclamantesAfiliado?->fecha_expedicion) }}" id="fecha_expedicion" placeholder="Fecha Expedicion">
            {!! $errors->first('fecha_expedicion', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="ciudad_expedicion" class="form-label">{{ __('Ciudad Expedicion') }}</label>
            <input type="text" name="ciudad_expedicion" class="form-control @error('ciudad_expedicion') is-invalid @enderror" value="{{ old('ciudad_expedicion', $reclamantesAfiliado?->ciudad_expedicion) }}" id="ciudad_expedicion" placeholder="Ciudad Expedicion">
            {!! $errors->first('ciudad_expedicion', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="departamento_expedicion" class="form-label">{{ __('Departamento Expedicion') }}</label>
            <input type="text" name="departamento_expedicion" class="form-control @error('departamento_expedicion') is-invalid @enderror" value="{{ old('departamento_expedicion', $reclamantesAfiliado?->departamento_expedicion) }}" id="departamento_expedicion" placeholder="Departamento Expedicion">
            {!! $errors->first('departamento_expedicion', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="estado_civil" class="form-label">{{ __('Estado Civil') }}</label>
            <input type="text" name="estado_civil" class="form-control @error('estado_civil') is-invalid @enderror" value="{{ old('estado_civil', $reclamantesAfiliado?->estado_civil) }}" id="estado_civil" placeholder="Estado Civil">
            {!! $errors->first('estado_civil', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="desde_convivencia" class="form-label">{{ __('Desde Convivencia') }}</label>
            <input type="text" name="desde_convivencia" class="form-control @error('desde_convivencia') is-invalid @enderror" value="{{ old('desde_convivencia', $reclamantesAfiliado?->desde_convivencia) }}" id="desde_convivencia" placeholder="Desde Convivencia">
            {!! $errors->first('desde_convivencia', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="hasta_convivencia" class="form-label">{{ __('Hasta Convivencia') }}</label>
            <input type="text" name="hasta_convivencia" class="form-control @error('hasta_convivencia') is-invalid @enderror" value="{{ old('hasta_convivencia', $reclamantesAfiliado?->hasta_convivencia) }}" id="hasta_convivencia" placeholder="Hasta Convivencia">
            {!! $errors->first('hasta_convivencia', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="compartieron_techo_mesa_lecho" class="form-label">{{ __('Compartieron Techo Mesa Lecho') }}</label>
            <input type="text" name="compartieron_techo_mesa_lecho" class="form-control @error('compartieron_techo_mesa_lecho') is-invalid @enderror" value="{{ old('compartieron_techo_mesa_lecho', $reclamantesAfiliado?->compartieron_techo_mesa_lecho) }}" id="compartieron_techo_mesa_lecho" placeholder="Compartieron Techo Mesa Lecho">
            {!! $errors->first('compartieron_techo_mesa_lecho', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="afiliado_relacion_quedaron_hijos" class="form-label">{{ __('Afiliado Relacion Quedaron Hijos') }}</label>
            <input type="text" name="afiliado_relacion_quedaron_hijos" class="form-control @error('afiliado_relacion_quedaron_hijos') is-invalid @enderror" value="{{ old('afiliado_relacion_quedaron_hijos', $reclamantesAfiliado?->afiliado_relacion_quedaron_hijos) }}" id="afiliado_relacion_quedaron_hijos" placeholder="Afiliado Relacion Quedaron Hijos">
            {!! $errors->first('afiliado_relacion_quedaron_hijos', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="datos_basicos_hijo" class="form-label">{{ __('Datos Basicos Hijo') }}</label>
            <input type="text" name="datos_basicos_hijo" class="form-control @error('datos_basicos_hijo') is-invalid @enderror" value="{{ old('datos_basicos_hijo', $reclamantesAfiliado?->datos_basicos_hijo) }}" id="datos_basicos_hijo" placeholder="Datos Basicos Hijo">
            {!! $errors->first('datos_basicos_hijo', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="direccion_siniestro" class="form-label">{{ __('Direccion Siniestro') }}</label>
            <input type="text" name="direccion_siniestro" class="form-control @error('direccion_siniestro') is-invalid @enderror" value="{{ old('direccion_siniestro', $reclamantesAfiliado?->direccion_siniestro) }}" id="direccion_siniestro" placeholder="Direccion Siniestro">
            {!! $errors->first('direccion_siniestro', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="direccion_actual" class="form-label">{{ __('Direccion Actual') }}</label>
            <input type="text" name="direccion_actual" class="form-control @error('direccion_actual') is-invalid @enderror" value="{{ old('direccion_actual', $reclamantesAfiliado?->direccion_actual) }}" id="direccion_actual" placeholder="Direccion Actual">
            {!! $errors->first('direccion_actual', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="barrio" class="form-label">{{ __('Barrio') }}</label>
            <input type="text" name="barrio" class="form-control @error('barrio') is-invalid @enderror" value="{{ old('barrio', $reclamantesAfiliado?->barrio) }}" id="barrio" placeholder="Barrio">
            {!! $errors->first('barrio', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="ciudad" class="form-label">{{ __('Ciudad') }}</label>
            <input type="text" name="ciudad" class="form-control @error('ciudad') is-invalid @enderror" value="{{ old('ciudad', $reclamantesAfiliado?->ciudad) }}" id="ciudad" placeholder="Ciudad">
            {!! $errors->first('ciudad', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="vivienda" class="form-label">{{ __('Vivienda') }}</label>
            <input type="text" name="vivienda" class="form-control @error('vivienda') is-invalid @enderror" value="{{ old('vivienda', $reclamantesAfiliado?->vivienda) }}" id="vivienda" placeholder="Vivienda">
            {!! $errors->first('vivienda', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="canon_arrendamiento" class="form-label">{{ __('Canon Arrendamiento') }}</label>
            <input type="text" name="canon_arrendamiento" class="form-control @error('canon_arrendamiento') is-invalid @enderror" value="{{ old('canon_arrendamiento', $reclamantesAfiliado?->canon_arrendamiento) }}" id="canon_arrendamiento" placeholder="Canon Arrendamiento">
            {!! $errors->first('canon_arrendamiento', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="tiempo_residencia" class="form-label">{{ __('Tiempo Residencia') }}</label>
            <input type="text" name="tiempo_residencia" class="form-control @error('tiempo_residencia') is-invalid @enderror" value="{{ old('tiempo_residencia', $reclamantesAfiliado?->tiempo_residencia) }}" id="tiempo_residencia" placeholder="Tiempo Residencia">
            {!! $errors->first('tiempo_residencia', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="movil" class="form-label">{{ __('Movil') }}</label>
            <input type="text" name="movil" class="form-control @error('movil') is-invalid @enderror" value="{{ old('movil', $reclamantesAfiliado?->movil) }}" id="movil" placeholder="Movil">
            {!! $errors->first('movil', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="activa_laboralmente_siniestro" class="form-label">{{ __('Activa Laboralmente Siniestro') }}</label>
            <input type="text" name="activa_laboralmente_siniestro" class="form-control @error('activa_laboralmente_siniestro') is-invalid @enderror" value="{{ old('activa_laboralmente_siniestro', $reclamantesAfiliado?->activa_laboralmente_siniestro) }}" id="activa_laboralmente_siniestro" placeholder="Activa Laboralmente Siniestro">
            {!! $errors->first('activa_laboralmente_siniestro', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="trabajaba_empresa" class="form-label">{{ __('Trabajaba Empresa') }}</label>
            <input type="text" name="trabajaba_empresa" class="form-control @error('trabajaba_empresa') is-invalid @enderror" value="{{ old('trabajaba_empresa', $reclamantesAfiliado?->trabajaba_empresa) }}" id="trabajaba_empresa" placeholder="Trabajaba Empresa">
            {!! $errors->first('trabajaba_empresa', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="ocupacion" class="form-label">{{ __('Ocupacion') }}</label>
            <input type="text" name="ocupacion" class="form-control @error('ocupacion') is-invalid @enderror" value="{{ old('ocupacion', $reclamantesAfiliado?->ocupacion) }}" id="ocupacion" placeholder="Ocupacion">
            {!! $errors->first('ocupacion', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="salario" class="form-label">{{ __('Salario') }}</label>
            <input type="text" name="salario" class="form-control @error('salario') is-invalid @enderror" value="{{ old('salario', $reclamantesAfiliado?->salario) }}" id="salario" placeholder="Salario">
            {!! $errors->first('salario', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="tiempo_trabajo" class="form-label">{{ __('Tiempo Trabajo') }}</label>
            <input type="text" name="tiempo_trabajo" class="form-control @error('tiempo_trabajo') is-invalid @enderror" value="{{ old('tiempo_trabajo', $reclamantesAfiliado?->tiempo_trabajo) }}" id="tiempo_trabajo" placeholder="Tiempo Trabajo">
            {!! $errors->first('tiempo_trabajo', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="coberturas_salud" class="form-label">{{ __('Coberturas Salud') }}</label>
            <input type="text" name="coberturas_salud" class="form-control @error('coberturas_salud') is-invalid @enderror" value="{{ old('coberturas_salud', $reclamantesAfiliado?->coberturas_salud) }}" id="coberturas_salud" placeholder="Coberturas Salud">
            {!! $errors->first('coberturas_salud', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="tipos_afiliaciones" class="form-label">{{ __('Tipos Afiliaciones') }}</label>
            <input type="text" name="tipos_afiliaciones" class="form-control @error('tipos_afiliaciones') is-invalid @enderror" value="{{ old('tipos_afiliaciones', $reclamantesAfiliado?->tipos_afiliaciones) }}" id="tipos_afiliaciones" placeholder="Tipos Afiliaciones">
            {!! $errors->first('tipos_afiliaciones', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="regimen" class="form-label">{{ __('Regimen') }}</label>
            <input type="text" name="regimen" class="form-control @error('regimen') is-invalid @enderror" value="{{ old('regimen', $reclamantesAfiliado?->regimen) }}" id="regimen" placeholder="Regimen">
            {!! $errors->first('regimen', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="estado_afiliacion" class="form-label">{{ __('Estado Afiliacion') }}</label>
            <input type="text" name="estado_afiliacion" class="form-control @error('estado_afiliacion') is-invalid @enderror" value="{{ old('estado_afiliacion', $reclamantesAfiliado?->estado_afiliacion) }}" id="estado_afiliacion" placeholder="Estado Afiliacion">
            {!! $errors->first('estado_afiliacion', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="registra_beneficiarios_eps" class="form-label">{{ __('Registra Beneficiarios Eps') }}</label>
            <input type="text" name="registra_beneficiarios_eps" class="form-control @error('registra_beneficiarios_eps') is-invalid @enderror" value="{{ old('registra_beneficiarios_eps', $reclamantesAfiliado?->registra_beneficiarios_eps) }}" id="registra_beneficiarios_eps" placeholder="Registra Beneficiarios Eps">
            {!! $errors->first('registra_beneficiarios_eps', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Siguiente') }}</button>
    </div>
</div>
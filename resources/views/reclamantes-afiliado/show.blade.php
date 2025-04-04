@extends('adminlte::page') 

@section('template_title')
    {{ $reclamantesAfiliado->name ?? __('Show') . " " . __('Reclamantes Afiliado') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Reclamantes Afiliado</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('reclamantes-afiliados.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Cedula Numero:</strong>
                                    {{ $reclamantesAfiliado->cedula_numero }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Reclamante:</strong>
                                    {{ $reclamantesAfiliado->reclamante }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Nombre:</strong>
                                    {{ $reclamantesAfiliado->nombre }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Tipo Documento:</strong>
                                    {{ $reclamantesAfiliado->tipo_documento }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Cedula Reclamante:</strong>
                                    {{ $reclamantesAfiliado->cedula_reclamante }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fecha Nacimiento:</strong>
                                    {{ $reclamantesAfiliado->fecha_nacimiento }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Ciudad Nacimiento:</strong>
                                    {{ $reclamantesAfiliado->ciudad_nacimiento }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Departamento Nacimiento:</strong>
                                    {{ $reclamantesAfiliado->departamento_nacimiento }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Edad:</strong>
                                    {{ $reclamantesAfiliado->edad }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fecha Expedicion:</strong>
                                    {{ $reclamantesAfiliado->fecha_expedicion }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Ciudad Expedicion:</strong>
                                    {{ $reclamantesAfiliado->ciudad_expedicion }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Departamento Expedicion:</strong>
                                    {{ $reclamantesAfiliado->departamento_expedicion }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Estado Civil:</strong>
                                    {{ $reclamantesAfiliado->estado_civil }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Desde Convivencia:</strong>
                                    {{ $reclamantesAfiliado->desde_convivencia }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Hasta Convivencia:</strong>
                                    {{ $reclamantesAfiliado->hasta_convivencia }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Compartieron Techo Mesa Lecho:</strong>
                                    {{ $reclamantesAfiliado->compartieron_techo_mesa_lecho }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Afiliado Relacion Quedaron Hijos:</strong>
                                    {{ $reclamantesAfiliado->afiliado_relacion_quedaron_hijos }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Datos Basicos Hijo:</strong>
                                    {{ $reclamantesAfiliado->datos_basicos_hijo }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Direccion Siniestro:</strong>
                                    {{ $reclamantesAfiliado->direccion_siniestro }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Direccion Actual:</strong>
                                    {{ $reclamantesAfiliado->direccion_actual }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Barrio:</strong>
                                    {{ $reclamantesAfiliado->barrio }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Ciudad:</strong>
                                    {{ $reclamantesAfiliado->ciudad }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Vivienda:</strong>
                                    {{ $reclamantesAfiliado->vivienda }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Canon Arrendamiento:</strong>
                                    {{ $reclamantesAfiliado->canon_arrendamiento }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Tiempo Residencia:</strong>
                                    {{ $reclamantesAfiliado->tiempo_residencia }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Movil:</strong>
                                    {{ $reclamantesAfiliado->movil }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Activa Laboralmente Siniestro:</strong>
                                    {{ $reclamantesAfiliado->activa_laboralmente_siniestro }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Trabajaba Empresa:</strong>
                                    {{ $reclamantesAfiliado->trabajaba_empresa }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Ocupacion:</strong>
                                    {{ $reclamantesAfiliado->ocupacion }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Salario:</strong>
                                    {{ $reclamantesAfiliado->salario }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Tiempo Trabajo:</strong>
                                    {{ $reclamantesAfiliado->tiempo_trabajo }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Coberturas Salud:</strong>
                                    {{ $reclamantesAfiliado->coberturas_salud }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Tipos Afiliaciones:</strong>
                                    {{ $reclamantesAfiliado->tipos_afiliaciones }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Regimen:</strong>
                                    {{ $reclamantesAfiliado->regimen }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Estado Afiliacion:</strong>
                                    {{ $reclamantesAfiliado->estado_afiliacion }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Registra Beneficiarios Eps:</strong>
                                    {{ $reclamantesAfiliado->registra_beneficiarios_eps }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

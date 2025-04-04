@extends('adminlte::page')

@section('template_title')
    {{ $personasAfiliada->name ?? __('Show') . " " . __('Personas Afiliada') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Personas Afiliada</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('personas-afiliadas.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Nombres Apellidos:</strong>
                                    {{ $personasAfiliada->nombres_apellidos }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Cedula:</strong>
                                    {{ $personasAfiliada->cedula }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Edad:</strong>
                                    {{ $personasAfiliada->edad }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fecha Nacimiento:</strong>
                                    {{ $personasAfiliada->fecha_nacimiento }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Lugar Nacimiento Id:</strong>
                                    {{ $personasAfiliada->lugar_nacimiento_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fecha Siniestro:</strong>
                                    {{ $personasAfiliada->fecha_siniestro }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Lugar Siniestro Id:</strong>
                                    {{ $personasAfiliada->lugar_siniestro_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Estado Civil Siniestro Id:</strong>
                                    {{ $personasAfiliada->estado_civil_siniestro_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Estado Civil Desde:</strong>
                                    {{ $personasAfiliada->estado_civil_desde }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Estado Civil Hasta:</strong>
                                    {{ $personasAfiliada->estado_civil_hasta }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Hijos:</strong>
                                    {{ $personasAfiliada->hijos }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Edad Hijos:</strong>
                                    {{ $personasAfiliada->edad_hijos }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Relacion Con:</strong>
                                    {{ $personasAfiliada->relacion_con }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Convivencia Con:</strong>
                                    {{ $personasAfiliada->convivencia_con }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Tiempo Convivencia:</strong>
                                    {{ $personasAfiliada->tiempo_convivencia }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Direccion Residencia:</strong>
                                    {{ $personasAfiliada->direccion_residencia }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Titular Trabaja:</strong>
                                    {{ $personasAfiliada->titular_trabaja }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Empresa:</strong>
                                    {{ $personasAfiliada->empresa }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Cargo:</strong>
                                    {{ $personasAfiliada->cargo }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Tiempo Laboral:</strong>
                                    {{ $personasAfiliada->tiempo_laboral }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Salario:</strong>
                                    {{ $personasAfiliada->salario }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Telefono:</strong>
                                    {{ $personasAfiliada->telefono }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Cobertura Salud Id:</strong>
                                    {{ $personasAfiliada->cobertura_salud_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Tipo Afiliacion Id:</strong>
                                    {{ $personasAfiliada->tipo_afiliacion_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Registra Beneficiarios:</strong>
                                    {{ $personasAfiliada->registra_beneficiarios }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Observaciones:</strong>
                                    {{ $personasAfiliada->observaciones }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

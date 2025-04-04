@extends('adminlte::page') 

@section('template_title')
    {{ $afiliadosConvivencia->name ?? __('Show') . " " . __('Afiliados Convivencia') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Afiliados Convivencia</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('afiliados-convivencias.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Cedula Numero:</strong>
                                    {{ $afiliadosConvivencia->cedula_numero }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Estado Civil Al Siniestro:</strong>
                                    {{ $afiliadosConvivencia->estado_civil_al_siniestro }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Desde Estado Civil:</strong>
                                    {{ $afiliadosConvivencia->desde_estado_civil }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Hasta Estado Civil:</strong>
                                    {{ $afiliadosConvivencia->hasta_estado_civil }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Relacion Con:</strong>
                                    {{ $afiliadosConvivencia->relacion_con }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Quien Convivía:</strong>
                                    {{ $afiliadosConvivencia->quien_convivía }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Tiempo Convivencia:</strong>
                                    {{ $afiliadosConvivencia->tiempo_convivencia }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Desde Convivencia:</strong>
                                    {{ $afiliadosConvivencia->desde_convivencia }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Hasta Convivencia:</strong>
                                    {{ $afiliadosConvivencia->hasta_convivencia }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

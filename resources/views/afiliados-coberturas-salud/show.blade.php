@extends('adminlte::page')

@section('template_title')
    {{ $afiliadosCoberturasSalud->name ?? __('Show') . " " . __('Afiliados Coberturas Salud') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Afiliados Coberturas Salud</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('afiliados-coberturas-saluds.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Cedula Numero:</strong>
                                    {{ $afiliadosCoberturasSalud->cedula_numero }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Cobertura Salud:</strong>
                                    {{ $afiliadosCoberturasSalud->cobertura_salud }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Tipo Afiliacion:</strong>
                                    {{ $afiliadosCoberturasSalud->tipo_afiliacion }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Regimen:</strong>
                                    {{ $afiliadosCoberturasSalud->regimen }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Desde:</strong>
                                    {{ $afiliadosCoberturasSalud->desde }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Registra Beneficiarios:</strong>
                                    {{ $afiliadosCoberturasSalud->registra_beneficiarios }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Quien Reclama Prestaciones Sociales:</strong>
                                    {{ $afiliadosCoberturasSalud->quien_reclama_prestaciones_sociales }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

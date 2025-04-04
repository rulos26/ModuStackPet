@extends('adminlte::page') 

@section('template_title')
    {{ $hechosOcurrencia->name ?? __('Show') . " " . __('Hechos Ocurrencia') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Hechos Ocurrencia</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('hechos-ocurrencias.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Cedula Numero:</strong>
                                    {{ $hechosOcurrencia->cedula_numero }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Dia:</strong>
                                    {{ $hechosOcurrencia->dia }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Horas:</strong>
                                    {{ $hechosOcurrencia->horas }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Lugar:</strong>
                                    {{ $hechosOcurrencia->lugar }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Motivo Muerte:</strong>
                                    {{ $hechosOcurrencia->motivo_muerte }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Otros:</strong>
                                    {{ $hechosOcurrencia->otros }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Deceso Se Origna:</strong>
                                    {{ $hechosOcurrencia->deceso_se_origna }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Donde Fallese:</strong>
                                    {{ $hechosOcurrencia->donde_fallese }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Funeraria:</strong>
                                    {{ $hechosOcurrencia->funeraria }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fallecido:</strong>
                                    {{ $hechosOcurrencia->fallecido }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Cuerpo Fue:</strong>
                                    {{ $hechosOcurrencia->cuerpo_fue }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Servicos Funerarios:</strong>
                                    {{ $hechosOcurrencia->servicos_funerarios }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

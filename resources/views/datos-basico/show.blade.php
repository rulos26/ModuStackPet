@extends('adminlte::page') 

@section('template_title')
    {{ $datosBasico->name ?? __('Show') . " " . __('Datos Basico') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Datos Basico</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('datos-basicos.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Cedula Numero:</strong>
                                    {{ $datosBasico->cedula_numero }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Nombre Afiliado:</strong>
                                    {{ $datosBasico->nombre_afiliado }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Caso:</strong>
                                    {{ $datosBasico->caso }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fecha:</strong>
                                    {{ $datosBasico->fecha }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Estado Civil:</strong>
                                    {{ $datosBasico->estado_civil }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Amparo:</strong>
                                    {{ $datosBasico->amparo }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Tipo De Convivencia:</strong>
                                    {{ $datosBasico->tipo_de_convivencia }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Otro:</strong>
                                    {{ $datosBasico->otro }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

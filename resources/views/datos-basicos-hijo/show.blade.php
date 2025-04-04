@extends('adminlte::page')

@section('template_title')
    {{ $datosBasicosHijo->name ?? __('Show') . " " . __('Datos Basicos Hijo') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Datos Basicos Hijo</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('datos-basicos-hijos.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Cedula Numero:</strong>
                                    {{ $datosBasicosHijo->cedula_numero }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Numero Hijos:</strong>
                                    {{ $datosBasicosHijo->numero_hijos }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Nombre:</strong>
                                    {{ $datosBasicosHijo->nombre }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Tipo Documento:</strong>
                                    {{ $datosBasicosHijo->tipo_documento }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Documento:</strong>
                                    {{ $datosBasicosHijo->documento }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Edad:</strong>
                                    {{ $datosBasicosHijo->edad }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@extends('layouts.app')

@section('template_title')
    {{ $datosAhorro->name ?? __('Show') . " " . __('Datos Ahorro') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Datos Ahorro</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('datos-ahorros.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>User Id:</strong>
                                    {{ $datosAhorro->user_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Sueldo:</strong>
                                    {{ $datosAhorro->sueldo }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Metodo Ahorro Id:</strong>
                                    {{ $datosAhorro->metodo_ahorro_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fecha Inicio:</strong>
                                    {{ $datosAhorro->fecha_inicio }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fecha Fin:</strong>
                                    {{ $datosAhorro->fecha_fin }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Mes Id:</strong>
                                    {{ $datosAhorro->mes_id }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

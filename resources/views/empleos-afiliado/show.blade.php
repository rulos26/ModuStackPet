@extends('adminlte::page')

@section('template_title')
    {{ $empleosAfiliado->name ?? __('Show') . " " . __('Empleos Afiliado') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Empleos Afiliado</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('empleos-afiliados.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Cedula Numero:</strong>
                                    {{ $empleosAfiliado->cedula_numero }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Afiliado Trabaja:</strong>
                                    {{ $empleosAfiliado->afiliado_trabaja }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Empresa:</strong>
                                    {{ $empleosAfiliado->empresa }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Cargo:</strong>
                                    {{ $empleosAfiliado->cargo }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Tiempo:</strong>
                                    {{ $empleosAfiliado->tiempo }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Salario:</strong>
                                    {{ $empleosAfiliado->salario }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>No Telefonico:</strong>
                                    {{ $empleosAfiliado->no_telefonico }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

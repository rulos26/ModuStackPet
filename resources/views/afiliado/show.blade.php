@extends('adminlte::page') 

@section('template_title')
    {{ $afiliado->name ?? __('Show') . " " . __('Afiliado') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Afiliado</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('afiliados.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Cedula Numero:</strong>
                                    {{ $afiliado->cedula_numero }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Edad:</strong>
                                    {{ $afiliado->edad }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fecha Nacimiento:</strong>
                                    {{ $afiliado->fecha_nacimiento }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Departamento:</strong>
                                    {{ $afiliado->departamento }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Municipio:</strong>
                                    {{ $afiliado->municipio }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fecha Expedicion:</strong>
                                    {{ $afiliado->fecha_expedicion }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@extends('adminlte::page') 

@section('template_title')
    {{ $siniestro->name ?? __('Show') . " " . __('Siniestro') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Siniestro</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('siniestros.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Cedula Numero:</strong>
                                    {{ $siniestro->cedula_numero }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fecha Siniestro:</strong>
                                    {{ $siniestro->fecha_siniestro }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Lugar:</strong>
                                    {{ $siniestro->lugar }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Departamento:</strong>
                                    {{ $siniestro->departamento }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Municipio:</strong>
                                    {{ $siniestro->municipio }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

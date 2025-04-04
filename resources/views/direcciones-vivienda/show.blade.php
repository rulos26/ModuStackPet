@extends('adminlte::page') 

@section('template_title')
    {{ $direccionesVivienda->name ?? __('Show') . " " . __('Direcciones Vivienda') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Direcciones Vivienda</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('direcciones-viviendas.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Cedula Numero:</strong>
                                    {{ $direccionesVivienda->cedula_numero }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Direccion Residencia:</strong>
                                    {{ $direccionesVivienda->direccion_residencia }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Tipo De Vivienda:</strong>
                                    {{ $direccionesVivienda->tipo_de_vivienda }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Tipo De Propiedad:</strong>
                                    {{ $direccionesVivienda->tipo_de_propiedad }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Vive Desde:</strong>
                                    {{ $direccionesVivienda->vive_desde }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

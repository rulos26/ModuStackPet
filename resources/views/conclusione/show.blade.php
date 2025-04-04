@extends('adminlte::page') 

@section('template_title')
    {{ $conclusione->name ?? __('Show') . " " . __('Conclusione') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Conclusione</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('conclusiones.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Cedula Numero:</strong>
                                    {{ $conclusione->cedula_numero }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Documentos:</strong>
                                    {{ $conclusione->documentos }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Nexos:</strong>
                                    {{ $conclusione->nexos }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Muerte Origen:</strong>
                                    {{ $conclusione->muerte_origen }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Reclamante:</strong>
                                    {{ $conclusione->reclamante }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Nombre Reclamante:</strong>
                                    {{ $conclusione->nombre_reclamante }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Afiliado Deja Descendiente:</strong>
                                    {{ $conclusione->afiliado_deja_descendiente }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Descendientes Relacion:</strong>
                                    {{ $conclusione->descendientes_relacion }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Descendientes Afiliado:</strong>
                                    {{ $conclusione->descendientes_afiliado }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Datos Hijo:</strong>
                                    {{ $conclusione->datos_hijo }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Presenta Condicion Discapacidad:</strong>
                                    {{ $conclusione->presenta_condicion_discapacidad }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Observaciones:</strong>
                                    {{ $conclusione->observaciones }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

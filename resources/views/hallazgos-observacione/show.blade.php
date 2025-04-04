@extends('adminlte::page')

@section('template_title')
    {{ $hallazgosObservacione->name ?? __('Show') . " " . __('Hallazgos Observacione') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Hallazgos Observacione</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('hallazgos-observaciones.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Cedula Numero:</strong>
                                    {{ $hallazgosObservacione->cedula_numero }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Hallazgos:</strong>
                                    {{ $hallazgosObservacione->hallazgos }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Observaciones:</strong>
                                    {{ $hallazgosObservacione->observaciones }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

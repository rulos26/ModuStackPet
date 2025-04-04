@extends('adminlte::page') 

@section('template_title')
    {{ $verificacione->name ?? __('Show') . " " . __('Verificacione') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Verificacione</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('verificaciones.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Cedula Numero:</strong>
                                    {{ $verificacione->cedula_numero }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Cedula Afiliado:</strong>
                                    {{ $verificacione->cedula_afiliado }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Registro Civil Nacimiento Afiliado:</strong>
                                    {{ $verificacione->registro_civil_nacimiento_afiliado }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Registro Defuncion Afiliado:</strong>
                                    {{ $verificacione->registro_defuncion_afiliado }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Cedula Reclamante:</strong>
                                    {{ $verificacione->cedula_reclamante }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Registro Civil Nacimiento Descendiente:</strong>
                                    {{ $verificacione->registro_civil_nacimiento_descendiente }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

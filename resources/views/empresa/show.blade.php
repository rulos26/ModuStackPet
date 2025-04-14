@extends('layouts.app')

@section('template_title')
    {{ $empresa->name ?? __('Show') . " " . __('Empresa') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Empresa</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('empresas.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Nombre Legal:</strong>
                                    {{ $empresa->nombre_legal }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Nombre Comercial:</strong>
                                    {{ $empresa->nombre_comercial }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Nit:</strong>
                                    {{ $empresa->nit }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Dv:</strong>
                                    {{ $empresa->dv }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Representante Legal:</strong>
                                    {{ $empresa->representante_legal }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Tipo Empresa Id:</strong>
                                    {{ $empresa->tipo_empresa_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Telefono:</strong>
                                    {{ $empresa->telefono }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Email:</strong>
                                    {{ $empresa->email }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Direccion:</strong>
                                    {{ $empresa->direccion }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Ciudad Id:</strong>
                                    {{ $empresa->ciudad_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Departamento Id:</strong>
                                    {{ $empresa->departamento_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Sector Id:</strong>
                                    {{ $empresa->sector_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Logo:</strong>
                                    {{ $empresa->logo }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Estado:</strong>
                                    {{ $empresa->estado }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

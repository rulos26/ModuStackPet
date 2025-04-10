@extends('layouts.app')

@section('template_title')
    {{ __('Detalles de la Raza') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Detalles de la Raza') }}</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('razas.index') }}">
                                <i class="fa fa-fw fa-arrow-left"></i> {{ __('Volver') }}
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="form-group">
                            <strong>{{ __('Tipo Mascota:') }}</strong>
                            {{ $raza->tipo_mascota }}
                        </div>
                        <div class="form-group">
                            <strong>{{ __('Nombre:') }}</strong>
                            {{ $raza->nombre }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

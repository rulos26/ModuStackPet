@extends('layouts.app')

@section('template_title')
    {{ __('Detalles del Barrio') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Detalles del Barrio') }}</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('barrios.index') }}"> {{ __('Volver') }}</a>
                        </div>
                    </div>

                    <div class="card-body">

                        <div class="form-group">
                            <strong>{{ __('Nombre:') }}</strong>
                            {{ $barrio->nombre }}
                        </div>
                        <div class="form-group">
                            <strong>{{ __('Localidad:') }}</strong>
                            {{ $barrio->localidad }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

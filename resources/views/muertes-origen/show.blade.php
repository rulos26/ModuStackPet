@extends('adminlte::page') 

@section('template_title')
    {{ $muertesOrigen->name ?? __('Show') . " " . __('Muertes Origen') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Muertes Origen</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('muertes-origens.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Nombre:</strong>
                                    {{ $muertesOrigen->nombre }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

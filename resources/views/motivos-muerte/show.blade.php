@extends('adminlte::page')

@section('template_title')
    {{ $motivosMuerte->name ?? __('Show') . " " . __('Motivos Muerte') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Motivos Muerte</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('motivos-muertes.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Nombre:</strong>
                                    {{ $motivosMuerte->nombre }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

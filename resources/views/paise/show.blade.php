@extends('adminlte::page') 

@section('template_title')
    {{ $paise->name ?? __('Show') . " " . __('Paise') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Paise</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('paises.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Name:</strong>
                                    {{ $paise->name }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Iso Name:</strong>
                                    {{ $paise->iso_name }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Alfa2:</strong>
                                    {{ $paise->alfa2 }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Alfa3:</strong>
                                    {{ $paise->alfa3 }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Numerico:</strong>
                                    {{ $paise->numerico }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

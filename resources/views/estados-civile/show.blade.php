@extends('adminlte::page')

@section('template_title')
    {{ $estadosCivile->name ?? __('Show') . " " . __('Estados Civile') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Estados Civile</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('estados-civiles.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Estado Civil:</strong>
                                    {{ $estadosCivile->estado_civil }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

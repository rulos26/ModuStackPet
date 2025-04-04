{{-- @extends('adminlte::page') 

@section('template_title')
    {{ $municipio->name ?? __('Show') . " " . __('Municipio') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Municipio</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('municipios.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Nombre:</strong>
                                    {{ $municipio->nombre }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Estado:</strong>
                                    {{ $municipio->estado }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Id Departamento:</strong>
                                    {{ $municipio->id_departamento }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
 --}}
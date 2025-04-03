@extends('adminlte::page')

@section('template_title', $metodosAhorro->name ?? __('Mostrar') . ' ' . __('Método de Ahorro'))

@section('content')
<section class="content container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 bg-dark text-white">
                <div class="card-header bg-secondary d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-uppercase font-weight-bold">
                        {{ __('Detalles del Método de Ahorro') }}
                    </h5>
                    <a class="btn btn-danger btn-sm" href="{{ route('metodos-ahorros.index') }}" style="border-radius: 8px;">
                        <i class="fas fa-arrow-left"></i> {{ __('Volver') }}
                    </a>
                </div>

                <div class="card-body bg-dark text-white">
                    <div class="form-group mb-3">
                        <strong class="text-warning">{{ __('Nombre del Método:') }}</strong>
                        <p class="mb-0">{{ $metodosAhorro->nom_metodo }}</p>
                    </div>

                    <div class="form-group mb-3">
                        <strong class="text-warning">{{ __('Descripción:') }}</strong>
                        <p class="mb-0">{{ $metodosAhorro->descripcion }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

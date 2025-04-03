@extends('adminlte::page')

@section('template_title')
    {{ $porcentajesAhorro->name ?? __('Mostrar') . " " . __('Porcentajes de Ahorro') }}
@endsection

@section('content')
<section class="content container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 bg-dark text-white" style="border-radius: 12px;">
                <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center" style="border-radius: 12px 12px 0 0;">
                    <h5 class="mb-0 text-uppercase font-weight-bold">
                        {{ __('Detalle del Porcentaje de Ahorro') }}
                    </h5>
                    <a href="{{ route('porcentajes-ahorros.index') }}" class="btn btn-danger btn-sm" style="border-radius: 8px;">
                        <i class="fas fa-arrow-left"></i> {{ __('Volver') }}
                    </a>
                </div>

                <div class="card-body bg-dark text-white p-4" style="border-radius: 0 0 12px 12px;">
                    <div class="mb-3">
                        <strong>📌 Método :</strong>
                        <span class="text-warning">{{ $porcentajesAhorro->metodo?->nom_metodo}}</span>
                    </div>
                    <div class="mb-3">
                        <strong>💰 Porcentaje 1:</strong>
                        <span class="text-success">{{ $porcentajesAhorro->porcentaje_1 }}%</span>
                    </div>
                    <div class="mb-3">
                        <strong>💰 Porcentaje 2:</strong>
                        <span class="text-success">{{ $porcentajesAhorro->porcentaje_2 }}%</span>
                    </div>
                    <div class="mb-3">
                        <strong>💰 Porcentaje 3:</strong>
                        <span class="text-success">{{ $porcentajesAhorro->porcentaje_3 }}%</span>
                    </div>
                    <div class="mb-3">
                        <strong>💰 Porcentaje 4:</strong>
                        <span class="text-success">{{ $porcentajesAhorro->porcentaje_4 }}%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Tarjeta principal -->
            <div class="card">
                <!-- Encabezado de la tarjeta -->
                <div class="card-header">
                    <h3 class="mb-0">{{ __('Crear Vacunas y Certificaciones') }}</h3>
                </div>

                <!-- Cuerpo de la tarjeta -->
                <div class="card-body">
                    <!-- Formulario de creación -->
                    <form action="{{ route('vacunas-certificaciones.store') }}" method="POST" enctype="multipart/form-data">
                        @include('vacunas-certificaciones.form')

                        <!-- Botones de acción -->
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> {{ __('Guardar') }}
                                </button>
                                <a href="{{ route('vacunas-certificaciones.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> {{ __('Cancelar') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

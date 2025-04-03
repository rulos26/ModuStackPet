@extends('adminlte::page')

@section('template_title', __('Crear Método de Ahorro'))

@section('content')
<section class="content container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 bg-dark text-white">
                <div class="card-header bg-secondary d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-uppercase font-weight-bold">
                        <i class="fas fa-plus-circle"></i> {{ __('Nuevo Método de Ahorro') }}
                    </h5>
                    <a class="btn btn-danger btn-sm" href="{{ route('metodos-ahorros.index') }}" style="border-radius: 8px;">
                        <i class="fas fa-arrow-left"></i> {{ __('Volver') }}
                    </a>
                </div>

                <div class="card-body bg-dark">
                    <form method="POST" action="{{ route('metodos-ahorros.store') }}" enctype="multipart/form-data">
                        @csrf

                        @include('metodos-ahorro.form')

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-success px-4" style="border-radius: 8px;">
                                <i class="fas fa-save"></i> {{ __('Guardar Método') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

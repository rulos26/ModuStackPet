@extends('adminlte::page')

@section('template_title', 'Actualizar Porcentaje de Ahorro')

@section('content')
<section class="content container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 bg-dark text-white" style="border-radius: 12px;">
                <div class="card-header bg-secondary text-white text-center" style="border-radius: 12px 12px 0 0;">
                    <h5 class="mb-0 text-uppercase font-weight-bold" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                        {{ __('Editar Porcentaje de Ahorro') }}
                    </h5>
                </div>
                <div class="card-body bg-dark text-white p-4" style="border-radius: 0 0 12px 12px;">
                    <form method="POST" action="{{ route('porcentajes-ahorros.update', $porcentajesAhorro->id) }}" role="form" enctype="multipart/form-data">
                        @method('PATCH')
                        @csrf
                        @include('porcentajes-ahorro.form')

                        <div class="d-flex justify-content-end mt-3">
                            <a href="{{ route('porcentajes-ahorros.index') }}" class="btn btn-light me-2" style="border-radius: 8px;">
                                <i class="fas fa-arrow-left"></i> {{ __('Cancelar') }}
                            </a>
                            <button type="submit" class="btn btn-success" style="border-radius: 8px;">
                                <i class="fas fa-save"></i> {{ __('Actualizar') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

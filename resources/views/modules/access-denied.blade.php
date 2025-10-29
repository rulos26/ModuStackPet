@extends('layouts.app')

@section('template_title')
    Acceso denegado
@endsection

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-danger">
                    <div class="card-header bg-danger text-white">
                        <i class="fas fa-lock"></i> Acceso denegado
                    </div>
                    <div class="card-body">
                        <p class="mb-0">
                            El módulo <strong>{{ $moduleName }}</strong> no está disponible actualmente. Contacte al administrador para más información.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection




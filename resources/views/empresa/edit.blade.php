@extends('layouts.app')

@section('template_title')
    {{ __('Actualizar') }} Empresa
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('Actualizar') }} Empresa</h3>
                        <div class="card-tools">
                            <a href="{{ route('empresas.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> {{ __('Volver') }}
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('empresas.update', $empresa->id) }}" role="form" enctype="multipart/form-data">
                            @method('PATCH')
                            @csrf
                            @include('empresa.form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .card {
        box-shadow: 0 0 1px rgba(0,0,0,.125), 0 0 2px rgba(0,0,0,.075);
    }
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid rgba(0,0,0,.125);
    }
    .card-title {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 600;
    }
    .card-tools {
        float: right;
    }
</style>
@endpush

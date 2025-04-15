@extends('layouts.app')

@section('template_title')
    Crear Nuevo Departamento
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="card-title">
                                <i class="fas fa-plus"></i> Crear Nuevo Departamento
                            </span>
                            <a href="{{ route('departamentos.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('departamentos.store') }}" role="form">
                            @csrf
                            @include('departamento.form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

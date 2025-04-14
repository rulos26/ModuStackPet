@extends('layouts.app')

@section('template_title')
    Crear Nuevo Registro de Vacunas y Certificaciones
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">
                                Crear Nuevo Registro de Vacunas y Certificaciones
                            </span>
                            <div class="float-right">
                                <a href="{{ route('vacunas_certificaciones.index') }}" class="btn btn-primary btn-sm float-right" data-placement="left">
                                    <i class="fas fa-arrow-left"></i> Volver
                                </a>
                            </div>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>¡Ups!</strong> Hubo algunos problemas con tu entrada.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="card-body">
                        <form method="POST" action="{{ route('vacunas_certificaciones.store') }}" role="form" enctype="multipart/form-data">
                            @csrf
                            @include('vacunas_certificaciones.form')
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

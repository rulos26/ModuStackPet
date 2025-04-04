@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">&nbsp;</h1> <!-- Espacio blanco -->
@stop

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span class="card-title">Firma Electrónica</span>
                    </div>
                    <div class="card-body bg-light text-center"> <!-- Centrar contenido -->
                        <div class="image-container">
                            @foreach ($imagePaths as $key => $imagePath)
                                <div class="mb-3">
                                    <!-- Quitar el nombre -->
                                    <img src="{{ $imagePath }}" alt="Imagen" class="img-fluid" style="border: 2px solid #ddd; padding: 5px; max-width: 100%; height: auto;"> <!-- Agrandar firma y borde más grueso -->
                                </div>
                            @endforeach
                        </div>
                        <div class="confirmation-message text-center mt-3 mb-3">
                            <p>¿Deseas proceder con esta firma?</p>
                        </div>
                        <div class="d-flex justify-content-center mt-3">
                            <a href="{{ route('firmas.edit', ['firma' => 1]) }}" class="btn btn-danger mx-2">No</a>
                            <a href="{{ route('reporte.carta') }}" class="btn btn-success mx-2">Sí</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

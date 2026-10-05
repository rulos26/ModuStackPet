@extends('layouts.app')

@section('template_title', 'Dashboard Paseador')

@section('content')
    <div class="container my-5">
        <!-- Tarjeta para el contenido -->
        <div class="card shadow-lg" style="border: 2px solid #000; border-radius: 15px;">
            <div class="card-body">
                <!-- Logo centrado y grande -->
                <div class="text-center">
                    @if(isset($logo) && !empty($logo))
                        <img src="{{ asset($logo) }}" alt="Logo Paseador" class="img-fluid" style="max-width: 200px; height: auto;">
                    @else
                        <img src="{{ asset('storage/img/logo.jpg') }}" alt="Logo Paseador" class="img-fluid" style="max-width: 200px; height: auto;">
                    @endif
                </div>

                <!-- Título centrado -->
                <h2 class="h1 text-center mt-4">🐾 {{ $titulo ?? 'Bienvenido a ModuStackPet' }}</h2>

                <!-- Descripción en formato de párrafos (alineada a la izquierda) -->
                <div class="mt-4">
                    @if(isset($descripcion) && !empty($descripcion))
                        @foreach (explode('.', str_replace(['\r\n', '\n', '\r'], ' ', $descripcion)) as $oracion)
                            @if (trim($oracion) !== '')
                                <p>{{ trim($oracion) }}.</p>
                            @endif
                        @endforeach
                    @else
                        <p>Gestiona tus paseos y servicios de manera profesional.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
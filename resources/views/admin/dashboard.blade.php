@extends('layouts.app')

@section('template_title', 'Dashboard Admin')

@section('content')
    <div class="container my-5">
        <!-- Tarjeta para el contenido -->
        <div class="card shadow-lg" style="border: 2px solid #000; border-radius: 15px;">
            <div class="card-body">
                <!-- Logo centrado y grande -->
                <div class="text-center">
                    <img src="{{ asset($logo) }}" alt="Logo Admin" class="img-fluid" style="max-width: 200px; height: auto;">
                </div>

                <!-- Título centrado -->
                <h2 class="h1 text-center mt-4">🐾 {{ $titulo }}</h2>

                <!-- Descripción en formato de párrafos (alineada a la izquierda) -->
                <div class="mt-4">
                    @foreach (explode('.', str_replace(['\r\n', '\n', '\r'], ' ', $descripcion)) as $oracion)
                        @if (trim($oracion) !== '')
                            <p>{{ trim($oracion) }}.</p>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
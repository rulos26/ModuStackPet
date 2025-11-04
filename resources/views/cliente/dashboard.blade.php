@extends('layouts.app')

@section('title', 'Dashboard Cliente')

@section('content')
    <div class="container my-5">
        <!-- Tarjeta para el contenido -->
        <div class="card shadow-lg" style="border: 2px solid #000; border-radius: 15px;">
            <div class="card-body">
                <!-- Logo centrado y grande -->
                <div class="text-center">
                    @if(isset($logo) && !empty($logo))
                        <img src="{{ asset($logo) }}" alt="Logo Cliente" class="img-fluid" style="max-width: 200px; height: auto;">
                    @else
                        <img src="{{ asset('storage/img/logo.jpg') }}" alt="Logo Cliente" class="img-fluid" style="max-width: 200px; height: auto;">
                    @endif
                </div>

                <!-- Título centrado -->
                <h1 class="text-center mt-4">🐾 {{ $titulo ?? 'Bienvenido a ModuStackPet' }}</h1>

                <!-- Descripción en formato de párrafos (alineada a la izquierda) -->
                <div class="mt-4">
                    @if(isset($descripcion) && !empty($descripcion))
                        @foreach (explode('.', $descripcion) as $oracion)
                            @if (trim($oracion) !== '')
                                <p>{{ trim($oracion) }}.</p>
                            @endif
                        @endforeach
                    @else
                        <p>Gestiona tus mascotas de manera fácil y rápida.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="text-center my-5">
        <!-- Logo centrado y grande -->
        <img src="{{ asset($logo) }}" alt="Logo Fortaleza Animal" class="img-fluid" style="max-width: 200px; height: auto;">

        <!-- Título centrado -->
        <h1 class="mt-4">🐾 {{ $titulo }}</h1>
    </div>

    <!-- Descripción en formato de párrafos (alineada a la izquierda) -->
    <div class="mt-3">
        @foreach (explode('.', $descripcion) as $oracion)
            @if (trim($oracion) !== '')
                <p>{{ trim($oracion) }}.</p>
            @endif
        @endforeach
    </div>
@endsection
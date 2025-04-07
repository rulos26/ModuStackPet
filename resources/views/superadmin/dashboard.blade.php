@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="text-center my-5">
        <!-- Logo centrado y grande -->
        <img src="{{ asset($logo) }}" alt="Logo Fortaleza Animal" class="img-fluid" style="max-width: 200px; height: auto;">

        <!-- Texto de bienvenida -->
        <h1 class="mt-4">🐾 {{ $titulo }}</h1>
        <p class="mt-3">
            {{ $descripcion }}
        </p>
    </div>
@endsection
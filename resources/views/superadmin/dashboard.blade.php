@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="text-center my-5">
        <!-- Logo centrado y grande -->
        <img src="{{ asset('public/storage/img/logo.jpg') }}" alt="Logo Fortaleza Animal" class="img-fluid" style="max-width: 200px; height: auto;">

        <!-- Texto de bienvenida -->
        <h1 class="mt-4">🐾 Bienvenidos a Fortaleza Animal – Guardería Canina y Felina en Bogotá</h1>
        <p class="mt-3">
            En Fortaleza Animal, más que una guardería, somos un refugio de amor, cuidado y diversión para tus mascotas. 
            Ubicados en Bogotá, ofrecemos un espacio seguro y lleno de cariño donde perros y gatos pueden socializar, 
            ejercitarse y recibir atención especializada mientras tú estás fuera.
        </p>
        <p>
            Contamos con amplias zonas , personal capacitado y una filosofía centrada en el bienestar físico y emocional 
            de cada uno de nuestros huéspedes peludos. Aquí, tu mascota no solo es bienvenida… ¡es parte de nuestra manada!
        </p>
    </div>
@endsection
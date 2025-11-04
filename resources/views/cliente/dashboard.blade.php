@extends('layouts.app')

@section('title', 'Dashboard Cliente')

@section('content')
    <div class="container my-5">
        <!-- Tarjeta para el contenido -->
        <div class="card shadow-lg" style="border: 2px solid #000; border-radius: 15px;">
            <div class="card-body">
                <!-- Foto de Perfil del Usuario -->
                <div class="text-center mb-4">
                    @php
                        $user = auth()->user();
                        $avatarUrl = asset('storage/img/default.png');
                        if ($user && $user->avatar) {
                            // La imagen se guarda en public/storage/img/avatar/filename.png
                            // La ruta en BD es: storage/img/avatar/filename.png
                            if (strpos($user->avatar, 'storage/img/avatar/') === 0) {
                                // Ruta nueva: storage/img/avatar/filename.png
                                $avatarUrl = asset($user->avatar);
                            } elseif (file_exists(public_path('storage/' . $user->avatar))) {
                                // Ruta antigua en storage
                                $avatarUrl = asset('storage/' . $user->avatar);
                            } elseif (file_exists(public_path($user->avatar))) {
                                // Ruta absoluta
                                $avatarUrl = asset('public/' . $user->avatar);
                            } elseif (\Illuminate\Support\Facades\Storage::disk('public')->exists($user->avatar)) {
                                // Ruta en storage disk
                                $avatarUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($user->avatar);
                            }
                        }
                    @endphp
                    <img src="{{ $avatarUrl }}" 
                         alt="Foto de Perfil" 
                         class="img-circle" 
                         style="width: 120px; height: 120px; object-fit: cover; border: 4px solid #007bff; border-radius: 50%;">
                    <h4 class="mt-3">{{ $user->name ?? 'Usuario' }}</h4>
                </div>

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
{{-- resources/views/auth/verify-email.blade.php --}}
@extends('layouts.app')

@section('template_title', 'Verifica tu correo electrónico')

@section('content')
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-body text-center">
            <h2 class="h3 mb-4">Verifica tu dirección de correo electrónico</h2>

            @if (session('status') == 'verification-link-sent')
                <div class="alert alert-success">
                    Se ha enviado un nuevo enlace de verificación a tu dirección de correo electrónico.
                </div>
            @endif

            <p>
                Antes de continuar, revisa tu correo electrónico para obtener el enlace de verificación.
                <br>Si no recibiste el correo,
            </p>

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn btn-primary">Haz clic aquí para reenviarlo</button>
            </form>
        </div>
    </div>
</div>
@endsection

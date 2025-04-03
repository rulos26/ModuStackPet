@extends('adminlte::page')

@section('title', 'Perfil de Usuario')

@section('content_header')
<h1>Perfil de {{ $user->name }}</h1>
@endsection

@section('content')
<div class="row">
    <!-- Tarjeta de Información del Usuario -->
    <div class="col-md-4">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile text-center">
                <img class="profile-user-img img-fluid img-circle"
                    src="{{ $user->profile_photo_url ?? asset('images/default-avatar.png') }}"
                    alt="Foto de {{ $user->name }}">
                <h3 class="profile-username">{{ $user->name }}</h3>
                <p class="text-muted">{{ $user->email  }}</p>
               <a href="{{ route('users.edit', $user->id ) }}" class="btn btn-primary btn-block"><b>Editar Perfil</b></a> 
            </div>
        </div>
    </div>

    <!-- Información Adicional -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-dark">
                <h3 class="card-title">Información Personal</h3>
            </div>
            <div class="card-body">
                <strong><i class="fas fa-id-badge mr-1"></i> Nombre Completo</strong>
                <p class="text-muted">{{ $user->name }}</p>
                <hr>

                <strong><i class="fas fa-envelope mr-1"></i> Correo Electrónico</strong>
                <p class="text-muted">{{ $user->email }}</p>
                <hr>

                <strong><i class="fas fa-calendar-alt mr-1"></i> Fecha de Registro</strong>
                <p class="text-muted">{{ $user->created_at->format('d M Y') }}</p>
                <hr>

                <strong><i class="fas fa-user-shield mr-1"></i> Rol</strong>
                <p class="text-muted">
                      @php
                    $role = $user->roles->first()->name ?? 'Usuario';
                    $roleColors = [
                    'Superadmin' => 'badge-danger', // Rojo
                    'Admin' => 'badge-primary', // Azul
                    'Cliente' => 'badge-success', // Verde
                    ];
                    $badgeClass = $roleColors[$role] ?? 'badge-secondary'; // Color por defecto si no hay rol
                    @endphp

                    <span class="badge {{ $badgeClass }}">{{ $role }}</span>


                </p>
            </div>
        </div>
    </div>
</div>
@endsection
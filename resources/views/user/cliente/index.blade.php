@extends('layouts.app')

@section('template_title')
    Mi Perfil
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">
                                <i class="fas fa-user"></i> {{ __('Mi Perfil') }}
                            </span>
                        </div>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="card-body">
                        @foreach ($users as $user)
                            <div class="row">
                                <div class="col-md-4 text-center">
                                    @if($user->avatar)
                                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="rounded-circle img-fluid mb-3" style="max-width: 200px;">
                                    @else
                                        <img src="{{ asset('img/default-avatar.png') }}" alt="Default Avatar" class="rounded-circle img-fluid mb-3" style="max-width: 200px;">
                                    @endif
                                </div>
                                <div class="col-md-8">
                                    <h3>{{ $user->name }}</h3>
                                    <p class="text-muted">{{ $user->email }}</p>

                                    <div class="row mt-4">
                                        <div class="col-md-6">
                                            <h5>Información Personal</h5>
                                            <ul class="list-unstyled">
                                                <li><strong>Cédula:</strong> {{ $user->cedula }}</li>
                                                <li><strong>Teléfono:</strong> {{ $user->telefono ?? 'No especificado' }}</li>
                                                <li><strong>WhatsApp:</strong> {{ $user->whatsapp ?? 'No especificado' }}</li>
                                                <li><strong>Fecha de Nacimiento:</strong> {{ $user->fecha_nacimiento ? \Carbon\Carbon::parse($user->fecha_nacimiento)->format('d/m/Y') : 'No especificada' }}</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h5>Estado de la Cuenta</h5>
                                            <ul class="list-unstyled">
                                                <li>
                                                    <strong>Estado:</strong>
                                                    <span class="badge {{ $user->activo ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $user->activo ? 'Activo' : 'Inactivo' }}
                                                    </span>
                                                </li>
                                                <li><strong>Rol:</strong>
                                                    @foreach($user->roles as $role)
                                                        <span class="badge bg-info">{{ $role->name }}</span>
                                                    @endforeach
                                                </li>
                                                <li><strong>Miembro desde:</strong> {{ $user->created_at->format('d/m/Y') }}</li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <a class="btn btn-success" href="{{ route('users.edit', $user->id) }}">
                                            <i class="fas fa-edit"></i> Editar Perfil
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

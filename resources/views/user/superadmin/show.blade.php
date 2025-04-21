@extends('layouts.app')

@section('template_title')
    {{ __('Información del Superadmin') }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="card-title mb-0">{{ __('Información del Superadmin') }}</h3>
                            <div class="card-tools">
                                <a href="{{ route('superadmin.users.edit') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i> {{ __('Editar') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Foto de Perfil y Datos Básicos -->
                            <div class="col-md-4">
                                <div class="text-center mb-4">
                                    @if($user->profile_photo_path)
                                        <img src="{{ asset('public/' . $user->profile_photo_path) }}" alt="Foto de perfil" class="img-fluid rounded-circle" style="max-width: 200px; border: 3px solid #dee2e6;">
                                    @else
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 200px; height: 200px;">
                                            <i class="fas fa-user fa-4x text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="text-center mb-4">
                                    <h4 class="mb-1">{{ $user->name }}</h4>
                                    <p class="text-muted mb-0">{{ $user->email }}</p>
                                </div>
                            </div>

                            <!-- Información Detallada -->
                            <div class="col-md-8">
                                <div class="row">
                                    <!-- Datos Personales -->
                                    <div class="col-md-6">
                                        <div class="card mb-4">
                                            <div class="card-header bg-light">
                                                <h5 class="card-title mb-0">{{ __('Datos Personales') }}</h5>
                                            </div>
                                            <div class="card-body">
                                                <dl class="row mb-0">
                                                    <dt class="col-sm-4">{{ __('Nombre') }}</dt>
                                                    <dd class="col-sm-8">{{ $user->name }}</dd>

                                                    <dt class="col-sm-4">{{ __('Email') }}</dt>
                                                    <dd class="col-sm-8">{{ $user->email }}</dd>

                                                    <dt class="col-sm-4">{{ __('Rol') }}</dt>
                                                    <dd class="col-sm-8">
                                                        <span class="badge bg-primary">
                                                            {{ __('Superadmin') }}
                                                        </span>
                                                    </dd>

                                                    <dt class="col-sm-4">{{ __('Estado') }}</dt>
                                                    <dd class="col-sm-8">
                                                        <span class="badge {{ $user->active ? 'bg-success' : 'bg-danger' }}">
                                                            {{ $user->active ? __('Activo') : __('Inactivo') }}
                                                        </span>
                                                    </dd>
                                                </dl>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Información de Acceso -->
                                    <div class="col-md-6">
                                        <div class="card mb-4">
                                            <div class="card-header bg-light">
                                                <h5 class="card-title mb-0">{{ __('Información de Acceso') }}</h5>
                                            </div>
                                            <div class="card-body">
                                                <dl class="row mb-0">
                                                    <dt class="col-sm-4">{{ __('Último Acceso') }}</dt>
                                                    <dd class="col-sm-8">{{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : __('Nunca') }}</dd>

                                                    <dt class="col-sm-4">{{ __('IP Último Acceso') }}</dt>
                                                    <dd class="col-sm-8">{{ $user->last_login_ip ?? __('No disponible') }}</dd>

                                                    <dt class="col-sm-4">{{ __('Fecha Creación') }}</dt>
                                                    <dd class="col-sm-8">{{ $user->created_at->format('d/m/Y') }}</dd>
                                                </dl>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card {
            box-shadow: 0 0 1px rgba(0,0,0,.125), 0 0 2px rgba(0,0,0,.075);
            border: none;
        }
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid rgba(0,0,0,.125);
        }
        .card-title {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 600;
        }
        dt {
            font-weight: 600;
            color: #6c757d;
        }
        .badge {
            font-size: 0.875rem;
            padding: 0.35em 0.65em;
        }
    </style>
@endsection

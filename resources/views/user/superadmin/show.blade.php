@extends('layouts.app')

@section('template_title')
    {{ __('Información del Superadmin') }}
@endsection

@section('content')
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="card-title mb-0">{{ __('Información del Superadmin') }}</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-info btn-sm me-2" data-bs-toggle="modal" data-bs-target="#infoModal">
                                    <i class="fas fa-info-circle"></i>
                                </button>
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
                                <div class="text-center mb-4 position-relative">
                                    <div class="profile-circle">
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 200px; height: 200px;">
                                            <i class="fas fa-user fa-4x text-muted"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center mb-4">
                                    <h4 class="mb-1">{{ $user->name }}</h4>
                                    <p class="text-muted mb-0">{{ $user->email }}</p>
                                    <button type="button" class="btn btn-warning btn-sm mt-3" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                        <i class="fas fa-key"></i> {{ __('Cambiar Contraseña') }}
                                    </button>
                                </div>
                            </div>

                            <!-- Información Detallada -->
                            <div class="col-md-8">
                                <div class="row">
                                    <!-- Información de Acceso -->
                                    <div class="col-md-12">
                                        <div class="card mb-4">
                                            <div class="card-header bg-light">
                                                <h5 class="card-title mb-0">{{ __('Información de Acceso') }}</h5>
                                            </div>
                                            <div class="card-body">
                                                <dl class="row mb-0">
                                                    <dt class="col-sm-4">{{ __('Rol') }}</dt>
                                                    <dd class="col-sm-8">
                                                        <span class="badge bg-primary">
                                                            {{ __('Superadmin') }}
                                                        </span>
                                                    </dd>

                                                    <dt class="col-sm-4">{{ __('Estado') }}</dt>
                                                    <dd class="col-sm-8">
                                                        <span class="badge {{ $user->activo ? 'bg-success' : 'bg-danger' }}">
                                                            {{ $user->activo ? __('Activo') : __('Inactivo') }}
                                                        </span>
                                                    </dd>

                                                    <dt class="col-sm-4">{{ __('Email Verificado') }}</dt>
                                                    <dd class="col-sm-8">
                                                        <span class="badge {{ $user->email_verified_at ? 'bg-success' : 'bg-warning' }}">
                                                            {{ $user->email_verified_at ? __('Verificado') : __('No verificado') }}
                                                        </span>
                                                    </dd>

                                                    <dt class="col-sm-4">{{ __('Fecha Creación') }}</dt>
                                                    <dd class="col-sm-8">{{ $user->created_at->format('d/m/Y H:i') }}</dd>

                                                    <dt class="col-sm-4">{{ __('Última Actualización') }}</dt>
                                                    <dd class="col-sm-8">{{ $user->updated_at->format('d/m/Y H:i') }}</dd>
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

    <!-- Modal de Información -->
    <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="infoModalLabel">{{ __('Información del Rol Superadmin') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6 class="fw-bold">{{ __('¿Qué es un Superadmin?') }}</h6>
                    <p>El Superadministrador es el rol con el máximo nivel de privilegios en el sistema. Este usuario tiene acceso completo a todas las funcionalidades y puede:</p>
                    <ul>
                        <li>Gestionar todos los usuarios del sistema</li>
                        <li>Configurar parámetros globales</li>
                        <li>Acceder a todas las secciones y módulos</li>
                        <li>Asignar y modificar roles de usuarios</li>
                        <li>Supervisar y auditar actividades del sistema</li>
                    </ul>
                    <p class="text-warning"><i class="fas fa-exclamation-triangle"></i> Este rol debe ser asignado con precaución debido a sus altos privilegios.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Cambiar Contraseña -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">{{ __('Cambiar Contraseña') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('superadmin.users.change-password') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">{{ __('Contraseña Actual') }}</label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                    id="current_password" name="current_password" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('current_password')">
                                    <i class="fas fa-eye" id="current_password_icon"></i>
                                </button>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('Nueva Contraseña') }}</label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" required onkeyup="checkPassword(this.value)">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                    <i class="fas fa-eye" id="password_icon"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="password-requirements mt-2">
                                <small class="text-muted">La contraseña debe cumplir con:</small>
                                <ul class="list-unstyled mb-0">
                                    <li><i class="fas fa-circle text-muted" id="length-check"></i> <small>Mínimo 10 caracteres</small></li>
                                    <li><i class="fas fa-circle text-muted" id="uppercase-check"></i> <small>Al menos una mayúscula</small></li>
                                    <li><i class="fas fa-circle text-muted" id="lowercase-check"></i> <small>Al menos una minúscula</small></li>
                                    <li><i class="fas fa-circle text-muted" id="number-check"></i> <small>Al menos un número</small></li>
                                    <li><i class="fas fa-circle text-muted" id="special-check"></i> <small>Al menos un carácter especial (@$!%*?&*)</small></li>
                                </ul>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">{{ __('Confirmar Nueva Contraseña') }}</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation" required onkeyup="checkPasswordMatch()">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                                    <i class="fas fa-eye" id="password_confirmation_icon"></i>
                                </button>
                            </div>
                            <div id="password-match-message" class="mt-1"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancelar') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Cambiar Contraseña') }}</button>
                    </div>
                </form>
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
        .profile-circle {
            position: relative;
            display: inline-block;
        }
        .profile-circle::before {
            content: '';
            position: absolute;
            top: -5px;
            left: -5px;
            right: -5px;
            bottom: -5px;
            border: 3px solid #dc3545;
            border-radius: 50%;
        }
        .me-2 {
            margin-right: 0.5rem;
        }
    </style>
@endsection

@push('scripts')
<script>
    // Si hay errores, mostrar el modal
    @if($errors->has('current_password') || $errors->has('password'))
        document.addEventListener('DOMContentLoaded', function() {
            var modal = new bootstrap.Modal(document.getElementById('changePasswordModal'));
            modal.show();
        });
    @endif

    // Función para mostrar/ocultar contraseña
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(inputId + '_icon');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    function checkPassword(password) {
        const lengthCheck = document.getElementById('length-check');
        const uppercaseCheck = document.getElementById('uppercase-check');
        const lowercaseCheck = document.getElementById('lowercase-check');
        const numberCheck = document.getElementById('number-check');
        const specialCheck = document.getElementById('special-check');

        // Verificar longitud
        if(password.length >= 10) {
            lengthCheck.classList.remove('text-muted');
            lengthCheck.classList.add('text-success');
        } else {
            lengthCheck.classList.remove('text-success');
            lengthCheck.classList.add('text-muted');
        }

        // Verificar mayúscula
        if(/[A-Z]/.test(password)) {
            uppercaseCheck.classList.remove('text-muted');
            uppercaseCheck.classList.add('text-success');
        } else {
            uppercaseCheck.classList.remove('text-success');
            uppercaseCheck.classList.add('text-muted');
        }

        // Verificar minúscula
        if(/[a-z]/.test(password)) {
            lowercaseCheck.classList.remove('text-muted');
            lowercaseCheck.classList.add('text-success');
        } else {
            lowercaseCheck.classList.remove('text-success');
            lowercaseCheck.classList.add('text-muted');
        }

        // Verificar número
        if(/[0-9]/.test(password)) {
            numberCheck.classList.remove('text-muted');
            numberCheck.classList.add('text-success');
        } else {
            numberCheck.classList.remove('text-success');
            numberCheck.classList.add('text-muted');
        }

        // Verificar carácter especial
        if(/[@$!%*?&*]/.test(password)) {
            specialCheck.classList.remove('text-muted');
            specialCheck.classList.add('text-success');
        } else {
            specialCheck.classList.remove('text-success');
            specialCheck.classList.add('text-muted');
        }

        // Verificar coincidencia si hay confirmación
        checkPasswordMatch();
    }

    function checkPasswordMatch() {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;
        const messageDiv = document.getElementById('password-match-message');

        if(confirmPassword.length > 0) {
            if(password === confirmPassword) {
                messageDiv.innerHTML = '<small class="text-success"><i class="fas fa-check"></i> Las contraseñas coinciden</small>';
            } else {
                messageDiv.innerHTML = '<small class="text-danger"><i class="fas fa-times"></i> Las contraseñas no coinciden</small>';
            }
        } else {
            messageDiv.innerHTML = '';
        }
    }
</script>
@endpush

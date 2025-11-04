@extends('layouts.app')

@section('template_title')
    Editar Configuración de Correo Electrónico
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">
                                <i class="fas fa-envelope"></i> Editar Configuración de Correo Electrónico
                            </span>
                            <div class="float-right">
                                <a href="{{ route('superadmin.email-configs.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-left"></i> Volver
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('superadmin.email-configs.update', $emailConfig) }}" role="form" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="mailer" class="form-label">Tipo de Mailer</label>
                                        <select class="form-control @error('mailer') is-invalid @enderror" id="mailer" name="mailer" required>
                                            <option value="smtp" {{ old('mailer', $emailConfig->mailer) == 'smtp' ? 'selected' : '' }}>SMTP</option>
                                            <option value="sendmail" {{ old('mailer', $emailConfig->mailer) == 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                                            <option value="mailgun" {{ old('mailer', $emailConfig->mailer) == 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                                            <option value="ses" {{ old('mailer', $emailConfig->mailer) == 'ses' ? 'selected' : '' }}>Amazon SES</option>
                                        </select>
                                        @error('mailer')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="host" class="form-label">Host SMTP</label>
                                        <input type="text" class="form-control @error('host') is-invalid @enderror" id="host" name="host" value="{{ old('host', $emailConfig->host) }}" required>
                                        @error('host')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="port" class="form-label">Puerto</label>
                                        <input type="number" class="form-control @error('port') is-invalid @enderror" id="port" name="port" value="{{ old('port', $emailConfig->port) }}" min="1" max="65535" required>
                                        <small class="form-text text-muted">587 para TLS, 465 para SSL</small>
                                        @error('port')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="encryption" class="form-label">Encriptación</label>
                                        <select class="form-control @error('encryption') is-invalid @enderror" id="encryption" name="encryption" required>
                                            <option value="tls" {{ old('encryption', $emailConfig->encryption) == 'tls' ? 'selected' : '' }}>TLS</option>
                                            <option value="ssl" {{ old('encryption', $emailConfig->encryption) == 'ssl' ? 'selected' : '' }}>SSL</option>
                                        </select>
                                        @error('encryption')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="username" class="form-label">Usuario SMTP</label>
                                        <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username', $emailConfig->username) }}" required>
                                        @error('username')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="password" class="form-label">Contraseña SMTP</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" value="" placeholder="Dejar vacío para mantener la actual">
                                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                                <i class="fas fa-eye" id="password-icon"></i>
                                            </button>
                                        </div>
                                        <small class="form-text text-muted">Deja vacío para mantener la contraseña actual</small>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="from_address" class="form-label">Email Remitente</label>
                                        <input type="email" class="form-control @error('from_address') is-invalid @enderror" id="from_address" name="from_address" value="{{ old('from_address', $emailConfig->from_address) }}" required>
                                        @error('from_address')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="from_name" class="form-label">Nombre del Remitente</label>
                                        <input type="text" class="form-control @error('from_name') is-invalid @enderror" id="from_name" name="from_name" value="{{ old('from_name', $emailConfig->from_name) }}">
                                        @error('from_name')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $emailConfig->is_active) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">
                                                <strong>Activar esta configuración</strong> (desactivará automáticamente las demás)
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Actualizar Configuración
                                    </button>
                                    <a href="{{ route('superadmin.email-configs.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Cancelar
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(inputId + '-icon');
            
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
    </script>
@endsection


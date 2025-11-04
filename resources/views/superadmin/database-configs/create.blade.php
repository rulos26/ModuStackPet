@extends('layouts.app')

@section('template_title')
    Crear Configuración de Base de Datos
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">
                                <i class="fas fa-database"></i> Crear Configuración de Base de Datos
                            </span>
                            <div class="float-right">
                                <a href="{{ route('superadmin.database-configs.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-left"></i> Volver
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('superadmin.database-configs.store') }}" role="form" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="connection" class="form-label">Tipo de Conexión</label>
                                        <select class="form-control @error('connection') is-invalid @enderror" id="connection" name="connection" required>
                                            <option value="mysql" {{ old('connection', 'mysql') == 'mysql' ? 'selected' : '' }}>MySQL</option>
                                            <option value="pgsql" {{ old('connection') == 'pgsql' ? 'selected' : '' }}>PostgreSQL</option>
                                            <option value="sqlite" {{ old('connection') == 'sqlite' ? 'selected' : '' }}>SQLite</option>
                                            <option value="sqlsrv" {{ old('connection') == 'sqlsrv' ? 'selected' : '' }}>SQL Server</option>
                                        </select>
                                        @error('connection')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="host" class="form-label">Host</label>
                                        <input type="text" class="form-control @error('host') is-invalid @enderror" id="host" name="host" value="{{ old('host', '127.0.0.1') }}" placeholder="127.0.0.1" required>
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
                                        <input type="number" class="form-control @error('port') is-invalid @enderror" id="port" name="port" value="{{ old('port', '3306') }}" min="1" max="65535" required>
                                        @error('port')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="database" class="form-label">Nombre de la Base de Datos</label>
                                        <input type="text" class="form-control @error('database') is-invalid @enderror" id="database" name="database" value="{{ old('database') }}" placeholder="nombre_bd" required>
                                        @error('database')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="username" class="form-label">Usuario</label>
                                        <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}" placeholder="usuario_bd" required>
                                        @error('username')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="password" class="form-label">Contraseña</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" value="{{ old('password') }}" placeholder="••••••••" required>
                                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                                <i class="fas fa-eye" id="password-icon"></i>
                                            </button>
                                        </div>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>
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
                                        <i class="fas fa-save"></i> Guardar Configuración
                                    </button>
                                    <a href="{{ route('superadmin.database-configs.index') }}" class="btn btn-secondary">
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


@extends('layouts.app')

@section('template_title')
    Editar Configuración de Backup
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">
                                <i class="fas fa-database"></i> Editar Configuración de Backup
                            </span>
                            <div class="float-right">
                                <a href="{{ route('superadmin.backup-configs.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-left"></i> Volver
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        @if(isset($productionDb))
                            <div class="alert alert-warning mb-4">
                                <i class="fas fa-exclamation-triangle"></i> 
                                <strong>Base de Datos de Producción:</strong> <code>{{ $productionDb }}</code>
                                <br>
                                <small>No puedes usar esta base de datos como destino. El sistema lo validará automáticamente.</small>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('superadmin.backup-configs.update', $backupConfig) }}" role="form" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="name" class="form-label">Nombre de la Configuración</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $backupConfig->name) }}" placeholder="Ej: Backup Diario" required>
                                        <small class="form-text text-muted">Nombre descriptivo para identificar esta configuración de backup</small>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <h5 class="mb-3"><i class="fas fa-server"></i> Configuración de Base de Datos Destino</h5>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="connection" class="form-label">Tipo de Conexión</label>
                                        <select class="form-control @error('connection') is-invalid @enderror" id="connection" name="connection" required>
                                            <option value="mysql" {{ old('connection', $backupConfig->connection) == 'mysql' ? 'selected' : '' }}>MySQL</option>
                                            <option value="pgsql" {{ old('connection', $backupConfig->connection) == 'pgsql' ? 'selected' : '' }}>PostgreSQL</option>
                                        </select>
                                        @error('connection')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="host" class="form-label">Host</label>
                                        <input type="text" class="form-control @error('host') is-invalid @enderror" id="host" name="host" value="{{ old('host', $backupConfig->host) }}" required>
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
                                        <input type="number" class="form-control @error('port') is-invalid @enderror" id="port" name="port" value="{{ old('port', $backupConfig->port) }}" min="1" max="65535" required>
                                        @error('port')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="database" class="form-label">Nombre de la Base de Datos Destino</label>
                                        <input type="text" class="form-control @error('database') is-invalid @enderror" id="database" name="database" value="{{ old('database', $backupConfig->database) }}" required>
                                        <small class="form-text text-muted">
                                            @if(isset($productionDb))
                                                <strong>NO usar:</strong> <code>{{ $productionDb }}</code>
                                            @endif
                                        </small>
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
                                        <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username', $backupConfig->username) }}" required>
                                        @error('username')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="password" class="form-label">Contraseña</label>
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

                            <hr>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="execute_seeders" name="execute_seeders" value="1" {{ old('execute_seeders', $backupConfig->execute_seeders) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="execute_seeders">
                                                <strong>Ejecutar Seeders después del Backup</strong>
                                                <br>
                                                <small class="text-muted">Si está marcado, se ejecutarán los seeders después de copiar los datos de producción</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $backupConfig->is_active) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">
                                                <strong>Activar esta configuración</strong>
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
                                    <a href="{{ route('superadmin.backup-configs.index') }}" class="btn btn-secondary">
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


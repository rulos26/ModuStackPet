@extends('layouts.app')

@section('template_title')
    Editar Proveedor OAuth
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">
                                <i class="fas fa-key"></i> Editar Proveedor OAuth: {{ $oauthProvider->name }}
                            </span>
                            <div class="float-right">
                                <a href="{{ route('superadmin.oauth-providers.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-left"></i> Volver
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('superadmin.oauth-providers.update', $oauthProvider) }}" role="form">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="provider" class="form-label">Provider</label>
                                        <input type="text" name="provider" id="provider" 
                                               class="form-control" 
                                               value="{{ $oauthProvider->provider }}" 
                                               disabled>
                                        <small class="form-text text-muted">El código del proveedor no puede modificarse</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="name" class="form-label">Nombre <span class="text-danger">*</span></label>
                                        <input type="text" name="name" id="name" 
                                               class="form-control @error('name') is-invalid @enderror" 
                                               value="{{ old('name', $oauthProvider->name) }}" 
                                               placeholder="Ej: Google" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="description" class="form-label">Descripción</label>
                                        <textarea name="description" id="description" 
                                                  class="form-control @error('description') is-invalid @enderror" 
                                                  rows="2" 
                                                  placeholder="Descripción del proveedor OAuth">{{ old('description', $oauthProvider->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <hr>
                                    <h5 class="mb-3">Credenciales OAuth</h5>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="client_id" class="form-label">Client ID</label>
                                        <input type="text" name="client_id" id="client_id" 
                                               class="form-control @error('client_id') is-invalid @enderror" 
                                               value="{{ old('client_id', $oauthProvider->client_id) }}" 
                                               placeholder="Ingrese el Client ID">
                                        @error('client_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="client_secret" class="form-label">Client Secret</label>
                                        <div class="input-group">
                                            <input type="password" name="client_secret" id="client_secret" 
                                                   class="form-control @error('client_secret') is-invalid @enderror" 
                                                   value="{{ old('client_secret', $oauthProvider->client_secret) }}" 
                                                   placeholder="Ingrese el Client Secret">
                                            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('client_secret')">
                                                <i class="fas fa-eye" id="eye_client_secret"></i>
                                            </button>
                                        </div>
                                        @if($oauthProvider->client_secret)
                                            <small class="form-text text-muted">Dejar en blanco para mantener el valor actual</small>
                                        @endif
                                        @error('client_secret')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="redirect_uri" class="form-label">Redirect URI</label>
                                        <input type="url" name="redirect_uri" id="redirect_uri" 
                                               class="form-control @error('redirect_uri') is-invalid @enderror" 
                                               value="{{ old('redirect_uri', $oauthProvider->redirect_uri) }}" 
                                               placeholder="https://tudominio.com/auth/{{ $oauthProvider->provider }}/callback">
                                        <small class="form-text text-muted">Esta URL debe coincidir con la configurada en el proveedor OAuth</small>
                                        @error('redirect_uri')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-check mb-3">
                                        <input type="checkbox" name="is_active" id="is_active" 
                                               class="form-check-input" 
                                               value="1" 
                                               {{ old('is_active', $oauthProvider->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Activar este proveedor
                                        </label>
                                        <small class="form-text text-muted d-block">El proveedor solo funcionará si está activo y tiene todas las credenciales configuradas</small>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="alert alert-{{ $oauthProvider->isConfigured() ? 'success' : 'warning' }}">
                                        <strong><i class="fas fa-{{ $oauthProvider->isConfigured() ? 'check-circle' : 'exclamation-triangle' }}"></i> Estado:</strong>
                                        @if($oauthProvider->isConfigured())
                                            <span class="ms-2">El proveedor está completamente configurado y listo para usar.</span>
                                        @else
                                            <span class="ms-2">El proveedor necesita completar las credenciales para funcionar correctamente.</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Actualizar Proveedor
                                    </button>
                                    <a href="{{ route('superadmin.oauth-providers.index') }}" class="btn btn-secondary">
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
        function togglePassword(id) {
            const input = document.getElementById(id);
            const eye = document.getElementById('eye_' + id);
            if (input.type === 'password') {
                input.type = 'text';
                eye.classList.remove('fa-eye');
                eye.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                eye.classList.remove('fa-eye-slash');
                eye.classList.add('fa-eye');
            }
        }

        // Si el campo client_secret está vacío al editar, no enviarlo (mantener valor actual)
        document.querySelector('form').addEventListener('submit', function(e) {
            const secretInput = document.getElementById('client_secret');
            if (secretInput.value === '') {
                secretInput.disabled = true;
            }
        });
    </script>
@endsection


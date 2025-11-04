@extends('layouts.app')

@section('template_title')
    Crear Proveedor OAuth
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">
                                <i class="fas fa-key"></i> Crear Nuevo Proveedor OAuth
                            </span>
                            <div class="float-right">
                                <a href="{{ route('superadmin.oauth-providers.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-left"></i> Volver
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('superadmin.oauth-providers.store') }}" role="form">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="provider" class="form-label">Provider <span class="text-danger">*</span></label>
                                        <select name="provider" id="provider" class="form-select @error('provider') is-invalid @enderror" required>
                                            <option value="">Seleccione un proveedor</option>
                                            <option value="google" {{ old('provider') === 'google' ? 'selected' : '' }}>Google</option>
                                            <option value="facebook" {{ old('provider') === 'facebook' ? 'selected' : '' }}>Facebook</option>
                                            <option value="github" {{ old('provider') === 'github' ? 'selected' : '' }}>GitHub</option>
                                            <option value="twitter" {{ old('provider') === 'twitter' ? 'selected' : '' }}>Twitter/X</option>
                                            <option value="linkedin" {{ old('provider') === 'linkedin' ? 'selected' : '' }}>LinkedIn</option>
                                        </select>
                                        <small class="form-text text-muted">Código técnico del proveedor (minúsculas, sin espacios)</small>
                                        @error('provider')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="name" class="form-label">Nombre <span class="text-danger">*</span></label>
                                        <input type="text" name="name" id="name" 
                                               class="form-control @error('name') is-invalid @enderror" 
                                               value="{{ old('name') }}" 
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
                                                  placeholder="Descripción del proveedor OAuth">{{ old('description') }}</textarea>
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
                                               value="{{ old('client_id') }}" 
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
                                                   value="{{ old('client_secret') }}" 
                                                   placeholder="Ingrese el Client Secret">
                                            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('client_secret')">
                                                <i class="fas fa-eye" id="eye_client_secret"></i>
                                            </button>
                                        </div>
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
                                               value="{{ old('redirect_uri', url('/auth/' . (old('provider') ?: 'google') . '/callback')) }}" 
                                               placeholder="https://tudominio.com/auth/google/callback">
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
                                               {{ old('is_active', false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Activar este proveedor
                                        </label>
                                        <small class="form-text text-muted d-block">El proveedor solo funcionará si está activo y tiene todas las credenciales configuradas</small>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="alert alert-info">
                                        <strong><i class="fas fa-info-circle"></i> Información:</strong>
                                        <ul class="mb-0 mt-2">
                                            <li><strong>Puedes tener múltiples providers configurados simultáneamente</strong> (Google, Facebook, GitHub, etc.)</li>
                                            <li>Obtén las credenciales desde el panel del proveedor (Google Cloud Console, Facebook Developers, etc.)</li>
                                            <li>El Redirect URI debe coincidir exactamente con el configurado en el proveedor</li>
                                            <li>Puedes guardar el proveedor sin credenciales y completarlas después</li>
                                            <li>Activa el provider solo cuando tengas todas las credenciales configuradas</li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Guardar Proveedor
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

        // Auto-generar redirect_uri cuando se selecciona provider
        document.getElementById('provider').addEventListener('change', function() {
            const provider = this.value;
            if (provider) {
                const redirectInput = document.getElementById('redirect_uri');
                const baseUrl = '{{ url("") }}';
                redirectInput.value = baseUrl + '/auth/' + provider + '/callback';
            }
        });
    </script>
@endsection


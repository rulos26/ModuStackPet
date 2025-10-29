@extends('layouts.app')

@section('template_title')
    Verificación de Seguridad
@endsection

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card border-warning">
                    <div class="card-header bg-warning text-dark">
                        <i class="fas fa-shield-alt"></i> Verificación de Seguridad
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Confirmar {{ $module->status ? 'Desactivación' : 'Activación' }} del Módulo</h5>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Módulo:</strong> {{ $module->name }}<br>
                            <strong>Acción:</strong> {{ $module->status ? 'Desactivar' : 'Activar' }}
                        </div>

                        <p>Se ha enviado un código de verificación de 6 dígitos a tu correo electrónico institucional.</p>

                        <form action="{{ route('superadmin.modules.confirm', $module) }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="verification_code" class="form-label">
                                    <i class="fas fa-key"></i> Código de Verificación
                                </label>
                                <input
                                    type="text"
                                    class="form-control @error('verification_code') is-invalid @enderror"
                                    id="verification_code"
                                    name="verification_code"
                                    placeholder="123456"
                                    maxlength="6"
                                    pattern="[0-9]{6}"
                                    required
                                    autocomplete="off"
                                >
                                @error('verification_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-{{ $module->status ? 'danger' : 'success' }}">
                                    <i class="fas fa-check"></i>
                                    Confirmar {{ $module->status ? 'Desactivación' : 'Activación' }}
                                </button>

                                <a href="{{ route('superadmin.modules.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Cancelar
                                </a>
                            </div>
                        </form>

                        <div class="mt-4">
                            <small class="text-muted">
                                <i class="fas fa-clock"></i> El código expira en 10 minutos.<br>
                                <i class="fas fa-envelope"></i> Si no recibiste el correo, revisa tu carpeta de spam.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-format verification code input
        document.getElementById('verification_code').addEventListener('input', function(e) {
            // Remove non-numeric characters
            e.target.value = e.target.value.replace(/[^0-9]/g, '');

            // Limit to 6 digits
            if (e.target.value.length > 6) {
                e.target.value = e.target.value.substring(0, 6);
            }
        });

        // Auto-submit when 6 digits are entered
        document.getElementById('verification_code').addEventListener('input', function(e) {
            if (e.target.value.length === 6) {
                // Small delay to show the complete code
                setTimeout(() => {
                    e.target.form.submit();
                }, 500);
            }
        });
    </script>
@endsection

@extends('layouts.app')

@section('template_title')
    Simulador Visual de Flujo OAuth
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-rocket text-primary"></i> Simulador Visual de Flujo OAuth
                        </h4>
                        <a href="{{ route('superadmin.oauth-providers.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    {{-- Step 1: Botón de inicio --}}
                    <div id="step-1" class="simulator-step active">
                        <div class="simulator-container">
                            <div class="text-center py-5">
                                <div class="mb-4">
                                    <h2 class="text-dark mb-3">Bienvenido a ModuStackPet</h2>
                                    <p class="text-muted">Inicia sesión para continuar</p>
                                </div>
                                
                                <div class="d-flex flex-column align-items-center gap-3 mb-4">
                                    <button class="btn-google-login" id="btn-start-oauth">
                                        <svg width="20" height="20" viewBox="0 0 24 24" class="me-2">
                                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                        </svg>
                                        Continuar con Google
                                    </button>
                                    
                                    <div class="text-muted small">
                                        <i class="fas fa-info-circle"></i> Simulación del flujo OAuth
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Step 2: Modal de selección de cuenta --}}
                    <div id="step-2" class="simulator-step">
                        <div class="simulator-container">
                            <div class="google-modal-overlay" id="google-modal">
                                <div class="google-modal">
                                    <div class="google-modal-header">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="mb-0">Seleccionar cuenta</h5>
                                                <small class="text-muted">Para continuar en ModuStackPet</small>
                                            </div>
                                            <button type="button" class="btn-close" onclick="resetSimulator()"></button>
                                        </div>
                                    </div>
                                    
                                    <div class="google-modal-body">
                                        <div class="account-list">
                                            <div class="account-item selected" onclick="selectAccount()">
                                                <img src="https://ui-avatars.com/api/?name=Julian+Ramirez&background=4285F4&color=fff&size=128" 
                                                     alt="Avatar" 
                                                     class="account-avatar">
                                                <div class="account-info">
                                                    <div class="account-name">Julian Ramirez</div>
                                                    <div class="account-email">julian.ramirez@catastrobogota.gov.co</div>
                                                </div>
                                                <div class="account-check">
                                                    <i class="fas fa-check-circle text-success"></i>
                                                </div>
                                            </div>
                                            
                                            <div class="account-divider">
                                                <span>Usar otra cuenta</span>
                                            </div>
                                            
                                            <div class="account-item" onclick="showLoginForm()">
                                                <div class="account-add">
                                                    <i class="fas fa-plus-circle"></i>
                                                </div>
                                                <div class="account-info">
                                                    <div class="account-name">Agregar cuenta</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="google-modal-footer">
                                        <button class="btn-google-secondary" onclick="resetSimulator()">Cancelar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Step 3: Pantalla de consentimiento --}}
                    <div id="step-3" class="simulator-step">
                        <div class="simulator-container">
                            <div class="google-modal-overlay">
                                <div class="google-modal">
                                    <div class="google-modal-header">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="mb-0">ModuStackPet quiere acceder a tu cuenta de Google</h5>
                                                <small class="text-muted">julian.ramirez@catastrobogota.gov.co</small>
                                            </div>
                                            <button type="button" class="btn-close" onclick="resetSimulator()"></button>
                                        </div>
                                    </div>
                                    
                                    <div class="google-modal-body">
                                        <div class="consent-content">
                                            <div class="app-info mb-4">
                                                <div class="app-logo">
                                                    <i class="fas fa-paw fa-3x text-primary"></i>
                                                </div>
                                                <div class="app-name">ModuStackPet</div>
                                                <div class="app-url">rulossoluciones.com</div>
                                            </div>
                                            
                                            <div class="consent-permissions">
                                                <p class="mb-3"><strong>Esta aplicación podrá:</strong></p>
                                                <div class="permission-item">
                                                    <i class="fas fa-check-circle text-success me-2"></i>
                                                    <span>Ver tu información básica del perfil</span>
                                                </div>
                                                <div class="permission-item">
                                                    <i class="fas fa-check-circle text-success me-2"></i>
                                                    <span>Ver tu dirección de correo electrónico</span>
                                                </div>
                                                <div class="permission-item">
                                                    <i class="fas fa-check-circle text-success me-2"></i>
                                                    <span>Ver tu foto de perfil</span>
                                                </div>
                                            </div>
                                            
                                            <div class="consent-warning mt-4">
                                                <i class="fas fa-shield-alt text-warning me-2"></i>
                                                <small class="text-muted">
                                                    Al continuar, ModuStackPet podrá acceder a la información que has compartido.
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="google-modal-footer">
                                        <button class="btn-google-secondary" onclick="goToStep(2)">Cancelar</button>
                                        <button class="btn-google-primary" onclick="goToStep(4)">
                                            <i class="fas fa-check me-2"></i> Permitir
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Step 4: Redirección --}}
                    <div id="step-4" class="simulator-step">
                        <div class="simulator-container">
                            <div class="text-center py-5">
                                <div class="mb-4">
                                    <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                                        <span class="visually-hidden">Cargando...</span>
                                    </div>
                                    <h4 class="text-dark mb-2">Redirigiendo a ModuStackPet...</h4>
                                    <p class="text-muted">Por favor espera mientras procesamos tu solicitud</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Step 5: Dashboard --}}
                    <div id="step-5" class="simulator-step">
                        <div class="simulator-container">
                            <div class="dashboard-mockup">
                                <div class="dashboard-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="dashboard-logo">
                                            <i class="fas fa-paw text-primary"></i>
                                            <span class="ms-2">ModuStackPet</span>
                                        </div>
                                        <div class="dashboard-user">
                                            <img src="https://ui-avatars.com/api/?name=Julian+Ramirez&background=4285F4&color=fff&size=128" 
                                                 alt="Avatar" 
                                                 class="user-avatar">
                                            <div class="user-info ms-2">
                                                <div class="user-name">Julian Ramirez</div>
                                                <div class="user-email">julian.ramirez@catastrobogota.gov.co</div>
                                            </div>
                                            <button class="btn-logout ms-3" onclick="resetSimulator()">
                                                <i class="fas fa-sign-out-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="dashboard-body">
                                    <div class="dashboard-sidebar">
                                        <div class="sidebar-header">
                                            <h6 class="mb-0">OAuth Providers</h6>
                                        </div>
                                        <div class="sidebar-content">
                                            <div class="provider-item connected">
                                                <div class="provider-icon">
                                                    <svg width="20" height="20" viewBox="0 0 24 24">
                                                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                                    </svg>
                                                </div>
                                                <div class="provider-name">Google</div>
                                                <div class="provider-status">
                                                    <i class="fas fa-check-circle text-success"></i>
                                                </div>
                                            </div>
                                            
                                            <div class="provider-item available">
                                                <div class="provider-icon">
                                                    <i class="fab fa-microsoft text-primary"></i>
                                                </div>
                                                <div class="provider-name">Microsoft</div>
                                                <div class="provider-status">
                                                    <span class="badge bg-secondary">Disponible</span>
                                                </div>
                                            </div>
                                            
                                            <div class="provider-item available">
                                                <div class="provider-icon">
                                                    <i class="fab fa-facebook text-primary"></i>
                                                </div>
                                                <div class="provider-name">Facebook</div>
                                                <div class="provider-status">
                                                    <span class="badge bg-secondary">Disponible</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="dashboard-content">
                                        <div class="welcome-card">
                                            <div class="card shadow-sm border-0">
                                                <div class="card-body text-center py-5">
                                                    <i class="fas fa-check-circle text-success fa-4x mb-3"></i>
                                                    <h3 class="text-dark mb-2">¡Bienvenido, Julian Ramirez!</h3>
                                                    <p class="text-muted mb-4">Has iniciado sesión exitosamente con Google</p>
                                                    <div class="d-flex justify-content-center gap-3">
                                                        <button class="btn btn-primary" onclick="resetSimulator()">
                                                            <i class="fas fa-redo me-2"></i> Reiniciar Simulación
                                                        </button>
                                                        <button class="btn btn-outline-primary" onclick="window.location.href='{{ route('superadmin.oauth-providers.index') }}'">
                                                            <i class="fas fa-arrow-left me-2"></i> Volver al Panel
                                                        </button>
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
        </div>
    </div>
</div>

<style>
    .simulator-step {
        display: none;
        min-height: 600px;
    }
    
    .simulator-step.active {
        display: block;
    }
    
    .simulator-container {
        padding: 2rem;
        background: #f8f9fa;
        min-height: 600px;
    }
    
    /* Botón Google */
    .btn-google-login {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 12px 24px;
        background: white;
        border: 1px solid #dadce0;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        color: #3c4043;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 1px 2px rgba(0,0,0,0.1);
    }
    
    .btn-google-login:hover {
        box-shadow: 0 2px 4px rgba(0,0,0,0.15);
        background: #f8f9fa;
    }
    
    /* Modal Google */
    .google-modal-overlay {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 500px;
    }
    
    .google-modal {
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.2);
        width: 100%;
        max-width: 400px;
        overflow: hidden;
    }
    
    .google-modal-header {
        padding: 16px 24px;
        border-bottom: 1px solid #e8eaed;
    }
    
    .google-modal-body {
        padding: 24px;
    }
    
    .google-modal-footer {
        padding: 16px 24px;
        border-top: 1px solid #e8eaed;
        display: flex;
        justify-content: flex-end;
        gap: 8px;
    }
    
    /* Lista de cuentas */
    .account-list {
        margin: 0;
        padding: 0;
        list-style: none;
    }
    
    .account-item {
        display: flex;
        align-items: center;
        padding: 12px;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.2s;
        margin-bottom: 8px;
    }
    
    .account-item:hover {
        background: #f1f3f4;
    }
    
    .account-item.selected {
        background: #e8f0fe;
    }
    
    .account-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-right: 12px;
    }
    
    .account-info {
        flex: 1;
    }
    
    .account-name {
        font-weight: 500;
        color: #202124;
        font-size: 14px;
    }
    
    .account-email {
        font-size: 13px;
        color: #5f6368;
    }
    
    .account-check {
        color: #1a73e8;
    }
    
    .account-add {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #f1f3f4;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        color: #5f6368;
    }
    
    .account-divider {
        text-align: center;
        margin: 16px 0;
        position: relative;
    }
    
    .account-divider::before {
        content: '';
        position: absolute;
        left: 0;
        right: 0;
        top: 50%;
        height: 1px;
        background: #e8eaed;
    }
    
    .account-divider span {
        background: white;
        padding: 0 16px;
        color: #5f6368;
        font-size: 12px;
        position: relative;
    }
    
    /* Botones Google */
    .btn-google-primary {
        background: #1a73e8;
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 4px;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.2s;
    }
    
    .btn-google-primary:hover {
        background: #1557b0;
    }
    
    .btn-google-secondary {
        background: transparent;
        color: #1a73e8;
        border: none;
        padding: 10px 24px;
        border-radius: 4px;
        font-weight: 500;
        cursor: pointer;
    }
    
    .btn-google-secondary:hover {
        background: #f1f3f4;
    }
    
    /* Consent Screen */
    .consent-content {
        text-align: center;
    }
    
    .app-logo {
        margin-bottom: 16px;
    }
    
    .app-name {
        font-size: 18px;
        font-weight: 500;
        color: #202124;
        margin-bottom: 4px;
    }
    
    .app-url {
        font-size: 13px;
        color: #5f6368;
        margin-bottom: 24px;
    }
    
    .consent-permissions {
        text-align: left;
        background: #f8f9fa;
        padding: 16px;
        border-radius: 8px;
        margin-bottom: 16px;
    }
    
    .permission-item {
        padding: 8px 0;
        font-size: 14px;
        color: #202124;
    }
    
    .consent-warning {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 12px;
        background: #fff3cd;
        border-radius: 4px;
    }
    
    /* Dashboard Mockup */
    .dashboard-mockup {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    
    .dashboard-header {
        padding: 16px 24px;
        border-bottom: 1px solid #e8eaed;
        background: white;
    }
    
    .dashboard-logo {
        font-size: 18px;
        font-weight: 500;
        color: #202124;
        display: flex;
        align-items: center;
    }
    
    .dashboard-user {
        display: flex;
        align-items: center;
    }
    
    .user-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
    }
    
    .user-name {
        font-size: 14px;
        font-weight: 500;
        color: #202124;
    }
    
    .user-email {
        font-size: 12px;
        color: #5f6368;
    }
    
    .btn-logout {
        background: transparent;
        border: 1px solid #dadce0;
        border-radius: 4px;
        padding: 6px 12px;
        color: #5f6368;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .btn-logout:hover {
        background: #f1f3f4;
    }
    
    .dashboard-body {
        display: flex;
        min-height: 500px;
    }
    
    .dashboard-sidebar {
        width: 250px;
        background: #f8f9fa;
        border-right: 1px solid #e8eaed;
        padding: 16px;
    }
    
    .sidebar-header {
        padding: 8px 0;
        border-bottom: 1px solid #e8eaed;
        margin-bottom: 16px;
    }
    
    .provider-item {
        display: flex;
        align-items: center;
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 8px;
        transition: background 0.2s;
    }
    
    .provider-item.connected {
        background: #e8f5e9;
    }
    
    .provider-item.available:hover {
        background: #f1f3f4;
    }
    
    .provider-icon {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
    }
    
    .provider-name {
        flex: 1;
        font-size: 14px;
        font-weight: 500;
        color: #202124;
    }
    
    .provider-status {
        font-size: 12px;
    }
    
    .dashboard-content {
        flex: 1;
        padding: 24px;
        background: white;
    }
    
    .welcome-card {
        max-width: 600px;
        margin: 0 auto;
    }
</style>

<script>
    let currentStep = 1;
    const totalSteps = 5;
    
    function goToStep(step) {
        // Ocultar paso actual
        document.getElementById(`step-${currentStep}`).classList.remove('active');
        
        // Mostrar nuevo paso
        currentStep = step;
        document.getElementById(`step-${currentStep}`).classList.add('active');
        
        // Si es el paso 4 (redirección), esperar 2 segundos y avanzar
        if (step === 4) {
            setTimeout(() => {
                goToStep(5);
            }, 2000);
        }
    }
    
    function selectAccount() {
        setTimeout(() => {
            goToStep(3);
        }, 500);
    }
    
    function showLoginForm() {
        // En una implementación real, mostraría un formulario de login
        alert('En una implementación real, aquí se mostraría un formulario de login de Google');
    }
    
    function resetSimulator() {
        goToStep(1);
    }
    
    // Inicializar
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('btn-start-oauth').addEventListener('click', function() {
            goToStep(2);
        });
    });
</script>
@endsection


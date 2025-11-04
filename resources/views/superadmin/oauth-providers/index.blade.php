@extends('layouts.app')

@section('template_title')
    Proveedores OAuth
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">
                                <i class="fas fa-key"></i> Gestión de Proveedores OAuth
                            </span>
                            <div class="float-right">
                                <a href="{{ route('superadmin.oauth-providers.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Nuevo Proveedor
                                </a>
                            </div>
                        </div>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show m-4" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show m-4" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                        </div>
                    @endif

                    <div class="alert alert-info m-4">
                        <h5 class="mb-2"><i class="fas fa-info-circle"></i> Módulo Multi-Provider</h5>
                        <p class="mb-2"><strong>Puedes configurar MÚLTIPLES proveedores OAuth simultáneamente:</strong></p>
                        <ul class="mb-0 small">
                            <li>✅ Configura Google, Facebook, GitHub, Twitter, LinkedIn y más <strong>al mismo tiempo</strong></li>
                            <li>✅ Cada provider tiene credenciales independientes</li>
                            <li>✅ Activa/desactiva cada uno individualmente</li>
                            <li>✅ Los botones aparecerán automáticamente en login/register solo para los activos</li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Provider</th>
                                        <th>Client ID</th>
                                        <th>Redirect URI</th>
                                        <th>Estado</th>
                                        <th>Configurado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($providers as $provider)
                                        <tr>
                                            <td>{{ $provider->id }}</td>
                                            <td><strong>{{ $provider->name }}</strong></td>
                                            <td><code>{{ $provider->provider }}</code></td>
                                            <td>
                                                @if($provider->client_id)
                                                    <span class="badge bg-success">Configurado</span>
                                                @else
                                                    <span class="badge bg-warning">Pendiente</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($provider->redirect_uri)
                                                    <small class="text-muted">{{ Str::limit($provider->redirect_uri, 40) }}</small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($provider->is_active)
                                                    <span class="badge bg-success">Activo</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactivo</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($provider->isConfigured())
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check-circle"></i> Completo
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-times-circle"></i> Incompleto
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('superadmin.oauth-providers.edit', $provider) }}" 
                                                       class="btn btn-sm btn-primary" 
                                                       title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    
                                                    <button type="button" 
                                                            class="btn btn-sm btn-info test-provider-btn" 
                                                            data-provider-id="{{ $provider->id }}"
                                                            data-provider-name="{{ $provider->name }}"
                                                            title="Probar Configuración">
                                                        <i class="fas fa-vial"></i>
                                                    </button>
                                                    
                                                    @if($provider->isConfigured() && $provider->is_active)
                                                    <button type="button" 
                                                            class="btn btn-sm btn-success simulate-connection-btn" 
                                                            data-provider-id="{{ $provider->id }}"
                                                            data-provider-name="{{ $provider->name }}"
                                                            title="Simular Conexión OAuth">
                                                        <i class="fas fa-plug"></i>
                                                    </button>
                                                    
                                                    <a href="{{ route('superadmin.oauth-providers.visual-simulator', $provider) }}" 
                                                       class="btn btn-sm btn-primary" 
                                                       title="Simulador Visual">
                                                        <i class="fas fa-rocket"></i>
                                                    </a>
                                                    @endif
                                                    
                                                    <form action="{{ route('superadmin.oauth-providers.toggle-status', $provider) }}" 
                                                          method="POST" 
                                                          style="display: inline;">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" 
                                                                class="btn btn-sm {{ $provider->is_active ? 'btn-warning' : 'btn-success' }}" 
                                                                title="{{ $provider->is_active ? 'Desactivar' : 'Activar' }}">
                                                            <i class="fas {{ $provider->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                                        </button>
                                                    </form>
                                                    
                                                    <form action="{{ route('superadmin.oauth-providers.destroy', $provider) }}" 
                                                          method="POST" 
                                                          style="display: inline;"
                                                          onsubmit="return confirm('¿Estás seguro de eliminar este proveedor?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="btn btn-sm btn-danger" 
                                                                title="Eliminar">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4">
                                                <p class="text-muted">No hay proveedores OAuth configurados.</p>
                                                <a href="{{ route('superadmin.oauth-providers.create') }}" class="btn btn-primary">
                                                    <i class="fas fa-plus"></i> Crear Primer Proveedor
                                                </a>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para simular conexión OAuth -->
    <div class="modal fade" id="simulateConnectionModal" tabindex="-1" aria-labelledby="simulateConnectionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="simulateConnectionModalLabel">
                        <i class="fas fa-plug"></i> Simulador de Conexión OAuth: <span id="simulate-provider-name"></span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div id="simulate-results-container">
                        <div class="text-center">
                            <div class="spinner-border text-success" role="status">
                                <span class="visually-hidden">Generando URL de autorización...</span>
                            </div>
                            <p class="mt-2">Generando URL de autorización...</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para mostrar resultados de prueba -->
    <div class="modal fade" id="testProviderModal" tabindex="-1" aria-labelledby="testProviderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="testProviderModalLabel">
                        <i class="fas fa-vial"></i> Resultados de Prueba: <span id="test-provider-name"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div id="test-results-container">
                        <div class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                            <p class="mt-2">Ejecutando pruebas...</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const testButtons = document.querySelectorAll('.test-provider-btn');
            const modal = new bootstrap.Modal(document.getElementById('testProviderModal'));
            const testResultsContainer = document.getElementById('test-results-container');
            const testProviderName = document.getElementById('test-provider-name');

            testButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const providerId = this.dataset.providerId;
                    const providerName = this.dataset.providerName;
                    
                    testProviderName.textContent = providerName;
                    testResultsContainer.innerHTML = `
                        <div class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                            <p class="mt-2">Ejecutando pruebas...</p>
                        </div>
                    `;
                    
                    modal.show();
                    
                    // Hacer petición AJAX
                    fetch(`{{ route('superadmin.oauth-providers.test', ['oauthProvider' => ':id']) }}`.replace(':id', providerId), {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Validar que data tiene la estructura esperada
                        if (!data || !data.tests || !Array.isArray(data.tests)) {
                            throw new Error('Respuesta inválida del servidor');
                        }
                        
                        let html = '';
                        
                        if (data.all_passed) {
                            html += `
                                <div class="alert alert-success">
                                    <h5><i class="fas fa-check-circle"></i> ¡Todas las pruebas pasaron!</h5>
                                    <p class="mb-0">El proveedor <strong>${data.provider}</strong> está correctamente configurado y listo para usar.</p>
                                </div>
                            `;
                        } else {
                            html += `
                                <div class="alert alert-warning">
                                    <h5><i class="fas fa-exclamation-triangle"></i> Algunas pruebas fallaron</h5>
                                    <p class="mb-0">El proveedor <strong>${data.provider}</strong> necesita ajustes en su configuración.</p>
                                </div>
                            `;
                        }
                        
                        html += '<div class="list-group mt-3">';
                        data.tests.forEach(test => {
                            const icon = test.status ? 'fa-check-circle text-success' : 'fa-times-circle text-danger';
                            const badge = test.status ? 'bg-success' : 'bg-danger';
                            html += `
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas ${icon} me-2"></i>
                                            <strong>${test.name}</strong>
                                            <p class="mb-0 small text-muted">${test.message}</p>
                                        </div>
                                        <span class="badge ${badge}">${test.status ? 'OK' : 'Error'}</span>
                                    </div>
                                </div>
                            `;
                        });
                        html += '</div>';
                        
                        testResultsContainer.innerHTML = html;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        testResultsContainer.innerHTML = `
                            <div class="alert alert-danger">
                                <h5><i class="fas fa-exclamation-circle"></i> Error al ejecutar pruebas</h5>
                                <p class="mb-0"><strong>Error:</strong> ${error.message}</p>
                                <p class="mb-0 small mt-2">Por favor, verifica la consola del navegador para más detalles.</p>
                            </div>
                        `;
                    });
                });
            });

            // Simulador de conexión OAuth
            const simulateButtons = document.querySelectorAll('.simulate-connection-btn');
            const simulateModal = new bootstrap.Modal(document.getElementById('simulateConnectionModal'));
            const simulateResultsContainer = document.getElementById('simulate-results-container');
            const simulateProviderName = document.getElementById('simulate-provider-name');

            simulateButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const providerId = this.dataset.providerId;
                    const providerName = this.dataset.providerName;
                    
                    simulateProviderName.textContent = providerName;
                    simulateResultsContainer.innerHTML = `
                        <div class="text-center">
                            <div class="spinner-border text-success" role="status">
                                <span class="visually-hidden">Generando URL de autorización...</span>
                            </div>
                            <p class="mt-2">Generando URL de autorización...</p>
                        </div>
                    `;
                    
                    simulateModal.show();
                    
                    // Hacer petición AJAX
                    fetch(`{{ route('superadmin.oauth-providers.simulate', ['oauthProvider' => ':id']) }}`.replace(':id', providerId), {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => Promise.reject(err));
                        }
                        return response.json();
                    })
                    .then(data => {
                        let html = '';
                        
                        if (data.success && data.authorization_url) {
                            // Guardar test_session_id en localStorage para referencia
                            if (data.test_session_id) {
                                localStorage.setItem('oauth_test_session_' + providerId, data.test_session_id);
                            }
                            
                            html += `
                                <div class="alert alert-success">
                                    <h5><i class="fas fa-check-circle"></i> Simulador de Conexión OAuth Listo</h5>
                                    <p class="mb-0">El sistema registrará <strong>todos los pasos</strong> del flujo OAuth completo.</p>
                                    <p class="mb-0 mt-2"><strong>ID de Sesión:</strong> <code>${data.test_session_id || 'N/A'}</code></p>
                                </div>
                                
                                <div class="card mb-3">
                                    <div class="card-header bg-primary text-white">
                                        <strong><i class="fas fa-link"></i> URL de Autorización</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control" id="auth-url-input" value="${data.authorization_url}" readonly>
                                            <button class="btn btn-outline-secondary" type="button" onclick="copyAuthUrl()">
                                                <i class="fas fa-copy"></i> Copiar
                                            </button>
                                        </div>
                                        <small class="text-muted">Haz clic en "Abrir en Nueva Pestaña" para iniciar el flujo completo de OAuth.</small>
                                    </div>
                                </div>
                                
                                <div class="card mb-3">
                                    <div class="card-header bg-info text-white">
                                        <strong><i class="fas fa-info-circle"></i> Instrucciones del Flujo Completo</strong>
                                    </div>
                                    <div class="card-body">
                                        <ol class="mb-0">
                                            ${data.instructions.map(instruction => `<li>${instruction}</li>`).join('')}
                                        </ol>
                                        <div class="alert alert-warning mt-3 mb-0">
                                            <strong><i class="fas fa-exclamation-triangle"></i> Importante:</strong>
                                            <p class="mb-0">El sistema registrará automáticamente cada paso en la base de datos. Después de autorizar, serás redirigido a un reporte completo con todos los pasos verificados.</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <a href="${data.authorization_url}" target="_blank" class="btn btn-success btn-lg">
                                        <i class="fas fa-external-link-alt"></i> Iniciar Flujo Completo OAuth
                                    </a>
                                    <button class="btn btn-outline-primary" onclick="copyAuthUrl()">
                                        <i class="fas fa-copy"></i> Copiar URL
                                    </button>
                                </div>
                                
                                <div class="alert alert-info mt-3">
                                    <strong><i class="fas fa-lightbulb"></i> ¿Qué se registrará?</strong>
                                    <ul class="mb-0 mt-2">
                                        <li>✅ URL de autorización generada</li>
                                        <li>✅ Callback recibido del provider</li>
                                        <li>✅ Datos del usuario obtenidos</li>
                                        <li>✅ Usuario creado/vinculado en BD</li>
                                        <li>✅ Sesión creada</li>
                                        <li>✅ Redirección al dashboard</li>
                                        <li>✅ Verificación de sesión</li>
                                        <li>✅ Flujo completo verificado</li>
                                    </ul>
                                    <p class="mb-0 mt-2"><strong>Redirect URI:</strong> <code>${data.redirect_uri}</code></p>
                                </div>
                            `;
                        } else {
                            html += `
                                <div class="alert alert-danger">
                                    <h5><i class="fas fa-exclamation-circle"></i> Error al Generar URL</h5>
                                    <p class="mb-0"><strong>Error:</strong> ${data.message || 'Error desconocido'}</p>
                                    ${data.error_details ? `<p class="mb-0 mt-2 small">${data.error_details}</p>` : ''}
                                </div>
                            `;
                        }
                        
                        simulateResultsContainer.innerHTML = html;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        const errorMessage = error.message || (error.error_details || 'Error desconocido');
                        simulateResultsContainer.innerHTML = `
                            <div class="alert alert-danger">
                                <h5><i class="fas fa-exclamation-circle"></i> Error al Simular Conexión</h5>
                                <p class="mb-0"><strong>Error:</strong> ${errorMessage}</p>
                                <p class="mb-0 small mt-2">Por favor, verifica la consola del navegador para más detalles.</p>
                            </div>
                        `;
                    });
                });
            });

            // Función para copiar URL de autorización
            function copyAuthUrl() {
                const input = document.getElementById('auth-url-input');
                if (input) {
                    input.select();
                    input.setSelectionRange(0, 99999); // Para móviles
                    document.execCommand('copy');
                    
                    // Mostrar feedback
                    const btn = event.target.closest('button');
                    const originalText = btn.innerHTML;
                    btn.innerHTML = '<i class="fas fa-check"></i> ¡Copiado!';
                    btn.classList.add('btn-success');
                    btn.classList.remove('btn-outline-secondary', 'btn-outline-primary');
                    
                    setTimeout(() => {
                        btn.innerHTML = originalText;
                        btn.classList.remove('btn-success');
                        btn.classList.add('btn-outline-secondary');
                    }, 2000);
                }
            }
        });
    </script>
@endsection


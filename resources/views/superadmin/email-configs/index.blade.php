@extends('layouts.app')

@section('template_title')
    Configuración de Correo Electrónico
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">
                                <i class="fas fa-envelope"></i> Configuración de Correo Electrónico
                            </span>
                            <div class="float-right">
                                <a href="{{ route('superadmin.email-configs.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Nueva Configuración
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

                    @if (session('test_result'))
                        @php
                            $result = session('test_result');
                        @endphp
                        <div class="alert alert-{{ $result['success'] ? 'success' : 'danger' }} alert-dismissible fade show m-4" role="alert">
                            <h5><i class="fas fa-{{ $result['success'] ? 'check-circle' : 'exclamation-circle' }}"></i> Resultado de Prueba</h5>
                            <p class="mb-0">{{ $result['message'] }}</p>
                            @if(isset($result['details']))
                                <small class="d-block mt-2">Detalles: {{ json_encode($result['details']) }}</small>
                            @endif
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                        </div>
                    @endif

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>ID</th>
                                        <th>Host SMTP</th>
                                        <th>Puerto</th>
                                        <th>Usuario</th>
                                        <th>Desde</th>
                                        <th>Encriptación</th>
                                        <th>Estado</th>
                                        <th>Última Prueba</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($configs as $config)
                                        <tr>
                                            <td>{{ $config->id }}</td>
                                            <td><strong>{{ $config->host }}</strong></td>
                                            <td>{{ $config->port }}</td>
                                            <td>{{ $config->username }}</td>
                                            <td><small>{{ $config->from_address }}</small></td>
                                            <td><span class="badge bg-info">{{ strtoupper($config->encryption) }}</span></td>
                                            <td>
                                                @if($config->is_active)
                                                    <span class="badge bg-success">Activo</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactivo</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($config->last_tested_at)
                                                    <small class="text-muted">
                                                        {{ $config->last_tested_at->diffForHumans() }}
                                                        @if($config->last_test_result)
                                                            @php
                                                                $testResult = json_decode($config->last_test_result, true);
                                                            @endphp
                                                            @if($testResult['success'] ?? false)
                                                                <i class="fas fa-check-circle text-success"></i>
                                                            @else
                                                                <i class="fas fa-times-circle text-danger"></i>
                                                            @endif
                                                        @endif
                                                    </small>
                                                @else
                                                    <span class="text-muted">Nunca</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('superadmin.email-configs.edit', $config) }}" 
                                                       class="btn btn-sm btn-primary" 
                                                       title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    
                                                    <button type="button" 
                                                            class="btn btn-sm btn-info test-email-btn" 
                                                            data-config-id="{{ $config->id }}"
                                                            data-config-host="{{ $config->host }}"
                                                            title="Probar Envío">
                                                        <i class="fas fa-paper-plane"></i>
                                                    </button>
                                                    
                                                    <form action="{{ route('superadmin.email-configs.toggle-status', $config) }}" 
                                                          method="POST" 
                                                          style="display: inline;">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" 
                                                                class="btn btn-sm {{ $config->is_active ? 'btn-warning' : 'btn-success' }}" 
                                                                title="{{ $config->is_active ? 'Desactivar' : 'Activar' }}">
                                                            <i class="fas {{ $config->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                                        </button>
                                                    </form>
                                                    
                                                    <form action="{{ route('superadmin.email-configs.destroy', $config) }}" 
                                                          method="POST" 
                                                          style="display: inline;"
                                                          onsubmit="return confirm('¿Estás seguro de eliminar esta configuración?');">
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
                                            <td colspan="9" class="text-center py-4">
                                                <p class="text-muted">No hay configuraciones de correo electrónico.</p>
                                                <a href="{{ route('superadmin.email-configs.create') }}" class="btn btn-primary">
                                                    <i class="fas fa-plus"></i> Crear Primera Configuración
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

    <!-- Modal para prueba de envío -->
    <div class="modal fade" id="testEmailModal" tabindex="-1" aria-labelledby="testEmailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="testEmailModalLabel">
                        <i class="fas fa-paper-plane"></i> Prueba de Envío: <span id="test-config-host"></span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="test_email" class="form-label">Email de Destino para Prueba</label>
                        <input type="email" class="form-control" id="test_email" placeholder="email@ejemplo.com" value="">
                    </div>
                    <div id="test-results-container">
                        <div class="text-center">
                            <p class="text-muted">Ingresa un email y haz clic en "Enviar Prueba"</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="send-test-btn">
                        <i class="fas fa-paper-plane"></i> Enviar Prueba
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const testButtons = document.querySelectorAll('.test-email-btn');
            const modal = new bootstrap.Modal(document.getElementById('testEmailModal'));
            const testResultsContainer = document.getElementById('test-results-container');
            const testConfigHost = document.getElementById('test-config-host');
            const sendTestBtn = document.getElementById('send-test-btn');
            let currentConfigId = null;

            testButtons.forEach(button => {
                button.addEventListener('click', function() {
                    currentConfigId = this.dataset.configId;
                    const configHost = this.dataset.configHost;
                    
                    testConfigHost.textContent = configHost;
                    testResultsContainer.innerHTML = `
                        <div class="text-center">
                            <p class="text-muted">Ingresa un email y haz clic en "Enviar Prueba"</p>
                        </div>
                    `;
                    document.getElementById('test_email').value = '';
                    
                    modal.show();
                });
            });

            sendTestBtn.addEventListener('click', function() {
                const testEmail = document.getElementById('test_email').value;
                
                if (!testEmail || !testEmail.includes('@')) {
                    testResultsContainer.innerHTML = `
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> Por favor, ingresa un email válido
                        </div>
                    `;
                    return;
                }

                testResultsContainer.innerHTML = `
                    <div class="text-center">
                        <div class="spinner-border text-info" role="status">
                            <span class="visually-hidden">Enviando correo...</span>
                        </div>
                        <p class="mt-2">Enviando correo de prueba a ${testEmail}...</p>
                    </div>
                `;

                const formData = new FormData();
                formData.append('test_email', testEmail);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.content || '');

                fetch(`{{ route('superadmin.email-configs.test', ['emailConfig' => ':id']) }}`.replace(':id', currentConfigId), {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => Promise.reject(err));
                    }
                    return response.json();
                })
                .then(data => {
                    let html = '';
                    
                    if (data.success) {
                        html += `
                            <div class="alert alert-success">
                                <h5><i class="fas fa-check-circle"></i> Correo Enviado Exitosamente</h5>
                                <p class="mb-0">${data.message}</p>
                            </div>
                            <div class="card mt-3">
                                <div class="card-header bg-success text-white">
                                    <strong>Detalles del Envío</strong>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled mb-0">
                                        <li><strong>Para:</strong> ${data.details.to}</li>
                                        <li><strong>Desde:</strong> ${data.details.from}</li>
                                        <li><strong>Host SMTP:</strong> ${data.details.host}</li>
                                        <li><strong>Puerto:</strong> ${data.details.port}</li>
                                    </ul>
                                    <p class="mb-0 mt-2"><small class="text-muted">Revisa la bandeja de entrada del email ${data.details.to}</small></p>
                                </div>
                            </div>
                        `;
                    } else {
                        html += `
                            <div class="alert alert-danger">
                                <h5><i class="fas fa-times-circle"></i> Error al Enviar</h5>
                                <p class="mb-0"><strong>Error:</strong> ${data.message}</p>
                                ${data.details ? `<p class="mb-0 mt-2 small">${JSON.stringify(data.details)}</p>` : ''}
                            </div>
                        `;
                    }
                    
                    testResultsContainer.innerHTML = html;
                })
                .catch(error => {
                    console.error('Error:', error);
                    const errorMessage = error.message || 'Error desconocido';
                    testResultsContainer.innerHTML = `
                        <div class="alert alert-danger">
                            <h5><i class="fas fa-exclamation-circle"></i> Error al Enviar Correo</h5>
                            <p class="mb-0"><strong>Error:</strong> ${errorMessage}</p>
                        </div>
                    `;
                });
            });
        });
    </script>
@endsection


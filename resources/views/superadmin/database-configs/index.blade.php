@extends('layouts.app')

@section('template_title')
    Configuración de Base de Datos
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">
                                <i class="fas fa-database"></i> Configuración de Base de Datos
                            </span>
                            <div class="float-right">
                                <a href="{{ route('superadmin.database-configs.create') }}" class="btn btn-primary btn-sm">
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
                                        <th>Host</th>
                                        <th>Puerto</th>
                                        <th>Base de Datos</th>
                                        <th>Usuario</th>
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
                                            <td><code>{{ $config->database }}</code></td>
                                            <td>{{ $config->username }}</td>
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
                                                    <a href="{{ route('superadmin.database-configs.edit', $config) }}" 
                                                       class="btn btn-sm btn-primary" 
                                                       title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    
                                                    <button type="button" 
                                                            class="btn btn-sm btn-info test-connection-btn" 
                                                            data-config-id="{{ $config->id }}"
                                                            data-config-host="{{ $config->host }}"
                                                            title="Probar Conexión">
                                                        <i class="fas fa-vial"></i>
                                                    </button>
                                                    
                                                    <form action="{{ route('superadmin.database-configs.toggle-status', $config) }}" 
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
                                                    
                                                    <form action="{{ route('superadmin.database-configs.destroy', $config) }}" 
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
                                            <td colspan="8" class="text-center py-4">
                                                <p class="text-muted">No hay configuraciones de base de datos.</p>
                                                <a href="{{ route('superadmin.database-configs.create') }}" class="btn btn-primary">
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

    <!-- Modal para resultados de prueba -->
    <div class="modal fade" id="testConnectionModal" tabindex="-1" aria-labelledby="testConnectionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="testConnectionModalLabel">
                        <i class="fas fa-vial"></i> Prueba de Conexión: <span id="test-config-host"></span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div id="test-results-container">
                        <div class="text-center">
                            <div class="spinner-border text-info" role="status">
                                <span class="visually-hidden">Probando conexión...</span>
                            </div>
                            <p class="mt-2">Probando conexión a la base de datos...</p>
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
            const testButtons = document.querySelectorAll('.test-connection-btn');
            const modal = new bootstrap.Modal(document.getElementById('testConnectionModal'));
            const testResultsContainer = document.getElementById('test-results-container');
            const testConfigHost = document.getElementById('test-config-host');

            testButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const configId = this.dataset.configId;
                    const configHost = this.dataset.configHost;
                    
                    testConfigHost.textContent = configHost;
                    testResultsContainer.innerHTML = `
                        <div class="text-center">
                            <div class="spinner-border text-info" role="status">
                                <span class="visually-hidden">Probando conexión...</span>
                            </div>
                            <p class="mt-2">Probando conexión a la base de datos...</p>
                        </div>
                    `;
                    
                    modal.show();
                    
                    fetch(`{{ route('superadmin.database-configs.test', ['databaseConfig' => ':id']) }}`.replace(':id', configId), {
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
                        
                        if (data.success) {
                            html += `
                                <div class="alert alert-success">
                                    <h5><i class="fas fa-check-circle"></i> Conexión Exitosa</h5>
                                    <p class="mb-0">${data.message}</p>
                                </div>
                                <div class="card mt-3">
                                    <div class="card-header bg-success text-white">
                                        <strong>Detalles de la Conexión</strong>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-unstyled mb-0">
                                            <li><strong>Host:</strong> ${data.details.host}</li>
                                            <li><strong>Base de Datos:</strong> ${data.details.database}</li>
                                            <li><strong>Usuario:</strong> ${data.details.username}</li>
                                            <li><strong>Puerto:</strong> ${data.details.port}</li>
                                        </ul>
                                    </div>
                                </div>
                            `;
                        } else {
                            html += `
                                <div class="alert alert-danger">
                                    <h5><i class="fas fa-times-circle"></i> Error de Conexión</h5>
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
                                <h5><i class="fas fa-exclamation-circle"></i> Error al Probar Conexión</h5>
                                <p class="mb-0"><strong>Error:</strong> ${errorMessage}</p>
                            </div>
                        `;
                    });
                });
            });
        });
    </script>
@endsection


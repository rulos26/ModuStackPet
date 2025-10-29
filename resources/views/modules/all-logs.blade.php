@extends('layouts.app')

@section('template_title')
    Logs de Auditoría del Sistema
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">
                                <i class="fas fa-history"></i> Logs de Auditoría del Sistema
                            </span>
                            <div>
                                <a href="{{ route('superadmin.modules.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-left"></i> Volver a Módulos
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Filtros -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <label for="action_filter" class="form-label">Filtrar por Acción:</label>
                                <select class="form-select" id="action_filter">
                                    <option value="">Todas las acciones</option>
                                    <option value="activated">Activado</option>
                                    <option value="deactivated">Desactivado</option>
                                    <option value="access_denied">Acceso Denegado</option>
                                    <option value="verification_sent">Código Enviado</option>
                                    <option value="verification_failed">Verificación Fallida</option>
                                    <option value="permission_changed">Permisos Cambiados</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="module_filter" class="form-label">Filtrar por Módulo:</label>
                                <select class="form-select" id="module_filter">
                                    <option value="">Todos los módulos</option>
                                    @foreach(\App\Models\Module::all() as $module)
                                        <option value="{{ $module->slug }}">{{ $module->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="date_filter" class="form-label">Últimos días:</label>
                                <select class="form-select" id="date_filter">
                                    <option value="7">Últimos 7 días</option>
                                    <option value="30">Últimos 30 días</option>
                                    <option value="90">Últimos 90 días</option>
                                    <option value="">Todos</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <div>
                                    <button type="button" class="btn btn-primary" onclick="applyFilters()">
                                        <i class="fas fa-filter"></i> Aplicar Filtros
                                    </button>
                                    <button type="button" class="btn btn-secondary" onclick="clearFilters()">
                                        <i class="fas fa-times"></i> Limpiar
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Estadísticas rápidas -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card bg-danger text-white">
                                    <div class="card-body text-center">
                                        <h5>{{ $accessDeniedLogs->count() }}</h5>
                                        <small>Accesos Denegados</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body text-center">
                                        <h5>{{ $failedVerificationLogs->count() }}</h5>
                                        <small>Verificaciones Fallidas</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center">
                                        <h5>{{ $logs->where('action', 'activated')->count() }}</h5>
                                        <small>Módulos Activados</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body text-center">
                                        <h5>{{ $logs->count() }}</h5>
                                        <small>Total de Eventos</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tabla de logs -->
                        @if($logs->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="logsTable">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Módulo</th>
                                            <th>Usuario</th>
                                            <th>Acción</th>
                                            <th>IP</th>
                                            <th>Detalles</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($logs as $log)
                                            <tr data-action="{{ $log->action }}" data-module="{{ $log->module->slug }}">
                                                <td>
                                                    <span class="badge bg-light text-dark">
                                                        {{ $log->timestamp->format('d/m/Y H:i:s') }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('superadmin.modules.logs', $log->module) }}" class="text-decoration-none">
                                                        <i class="fas fa-puzzle-piece"></i> {{ $log->module->name }}
                                                    </a>
                                                </td>
                                                <td>
                                                    @if($log->user)
                                                        <i class="fas fa-user"></i> {{ $log->user->name }}
                                                        <br><small class="text-muted">{{ $log->user->email }}</small>
                                                    @else
                                                        <span class="text-muted">Usuario no autenticado</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @switch($log->action)
                                                        @case('activated')
                                                            <span class="badge bg-success">
                                                                <i class="fas fa-check"></i> Activado
                                                            </span>
                                                            @break
                                                        @case('deactivated')
                                                            <span class="badge bg-danger">
                                                                <i class="fas fa-ban"></i> Desactivado
                                                            </span>
                                                            @break
                                                        @case('access_denied')
                                                            <span class="badge bg-warning">
                                                                <i class="fas fa-lock"></i> Acceso Denegado
                                                            </span>
                                                            @break
                                                        @case('verification_sent')
                                                            <span class="badge bg-info">
                                                                <i class="fas fa-envelope"></i> Código Enviado
                                                            </span>
                                                            @break
                                                        @case('verification_failed')
                                                            <span class="badge bg-danger">
                                                                <i class="fas fa-times"></i> Verificación Fallida
                                                            </span>
                                                            @break
                                                        @case('permission_changed')
                                                            <span class="badge bg-primary">
                                                                <i class="fas fa-key"></i> Permisos Cambiados
                                                            </span>
                                                            @break
                                                        @default
                                                            <span class="badge bg-secondary">{{ $log->action }}</span>
                                                    @endswitch
                                                </td>
                                                <td>
                                                    <code>{{ $log->ip_address }}</code>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-info" onclick="showDetails('{{ $log->id }}')">
                                                        <i class="fas fa-eye"></i> Ver
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle"></i>
                                No hay logs de auditoría disponibles.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para detalles -->
    <div class="modal fade" id="detailsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalles del Log</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="detailsContent">
                    <!-- Contenido dinámico -->
                </div>
            </div>
        </div>
    </div>

    <script>
        function applyFilters() {
            const actionFilter = document.getElementById('action_filter').value;
            const moduleFilter = document.getElementById('module_filter').value;
            const dateFilter = document.getElementById('date_filter').value;

            const rows = document.querySelectorAll('#logsTable tbody tr');

            rows.forEach(row => {
                let show = true;

                if (actionFilter && row.dataset.action !== actionFilter) {
                    show = false;
                }

                if (moduleFilter && row.dataset.module !== moduleFilter) {
                    show = false;
                }

                row.style.display = show ? '' : 'none';
            });
        }

        function clearFilters() {
            document.getElementById('action_filter').value = '';
            document.getElementById('module_filter').value = '';
            document.getElementById('date_filter').value = '7';

            const rows = document.querySelectorAll('#logsTable tbody tr');
            rows.forEach(row => {
                row.style.display = '';
            });
        }

        function showDetails(logId) {
            // Aquí podrías hacer una petición AJAX para obtener más detalles
            document.getElementById('detailsContent').innerHTML = `
                <p>ID del Log: ${logId}</p>
                <p>Esta funcionalidad se puede expandir para mostrar más detalles específicos del log.</p>
            `;

            new bootstrap.Modal(document.getElementById('detailsModal')).show();
        }
    </script>
@endsection

@extends('layouts.app')

@section('template_title')
    Logs de Backup - {{ $backupConfig->name }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">
                                <i class="fas fa-history"></i> Logs de Backup - {{ $backupConfig->name }}
                            </span>
                            <div class="float-right">
                                <a href="{{ route('superadmin.backup-configs.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-left"></i> Volver
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="alert alert-info mb-4">
                            <i class="fas fa-info-circle"></i> 
                            <strong>Configuración:</strong> {{ $backupConfig->name }}
                            <br>
                            <strong>Base de Datos Destino:</strong> <code>{{ $backupConfig->database }}</code>
                            <br>
                            <strong>Último Backup:</strong> 
                            @if($backupConfig->last_backup_at)
                                {{ $backupConfig->last_backup_at->diffForHumans() }}
                            @else
                                <span class="text-muted">Nunca</span>
                            @endif
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>ID</th>
                                        <th>Estado</th>
                                        <th>Tablas</th>
                                        <th>Registros</th>
                                        <th>Usuario</th>
                                        <th>Iniciado</th>
                                        <th>Completado</th>
                                        <th>Duración</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($logs as $log)
                                        <tr>
                                            <td>{{ $log->id }}</td>
                                            <td>
                                                @if($log->status === 'completed')
                                                    <span class="badge bg-success">Completado</span>
                                                @elseif($log->status === 'failed')
                                                    <span class="badge bg-danger">Fallido</span>
                                                @elseif($log->status === 'in_progress')
                                                    <span class="badge bg-warning">En Progreso</span>
                                                @else
                                                    <span class="badge bg-secondary">Pendiente</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $log->tables_backed_up }} / {{ $log->tables_total }}
                                                @if($log->tables_total > 0)
                                                    <br>
                                                    <small class="text-muted">
                                                        {{ number_format(($log->tables_backed_up / $log->tables_total) * 100, 1) }}%
                                                    </small>
                                                @endif
                                            </td>
                                            <td>
                                                {{ number_format($log->records_backed_up, 0, ',', '.') }}
                                            </td>
                                            <td>
                                                @if($log->user)
                                                    {{ $log->user->name }}
                                                @else
                                                    <span class="text-muted">Sistema</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($log->started_at)
                                                    <small>{{ $log->started_at->format('d/m/Y H:i:s') }}</small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($log->completed_at)
                                                    <small>{{ $log->completed_at->format('d/m/Y H:i:s') }}</small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($log->started_at && $log->completed_at)
                                                    <small>{{ $log->started_at->diffForHumans($log->completed_at, true) }}</small>
                                                @elseif($log->started_at)
                                                    <small class="text-warning">En progreso...</small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($log->error_message || ($log->details && count($log->details) > 0))
                                                    <button type="button" 
                                                            class="btn btn-sm btn-info" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#logDetailsModal{{ $log->id }}"
                                                            title="Ver Detalles">
                                                        <i class="fas fa-info-circle"></i>
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>

                                        <!-- Modal para detalles del log -->
                                        @if($log->error_message || ($log->details && count($log->details) > 0))
                                            <div class="modal fade" id="logDetailsModal{{ $log->id }}" tabindex="-1" aria-labelledby="logDetailsModalLabel{{ $log->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="logDetailsModalLabel{{ $log->id }}">
                                                                Detalles del Backup #{{ $log->id }}
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            @if($log->error_message)
                                                                <div class="alert alert-danger">
                                                                    <h6><i class="fas fa-exclamation-circle"></i> Error</h6>
                                                                    <p class="mb-0">{{ $log->error_message }}</p>
                                                                </div>
                                                            @endif

                                                            @if($log->details && count($log->details) > 0)
                                                                <h6>Detalles Adicionales:</h6>
                                                                <pre class="bg-light p-3 rounded">{{ json_encode($log->details, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                            @endif

                                                            <div class="row mt-3">
                                                                <div class="col-md-6">
                                                                    <strong>Iniciado:</strong><br>
                                                                    <small>{{ $log->started_at ? $log->started_at->format('d/m/Y H:i:s') : '-' }}</small>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <strong>Completado:</strong><br>
                                                                    <small>{{ $log->completed_at ? $log->completed_at->format('d/m/Y H:i:s') : '-' }}</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center py-4">
                                                <p class="text-muted">No hay logs de backup para esta configuración.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginación -->
                        <div class="mt-4">
                            {{ $logs->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


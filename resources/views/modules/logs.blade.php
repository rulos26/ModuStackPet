@extends('layouts.app')

@section('template_title')
    Logs de Auditoría - {{ $module->name }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">
                                <i class="fas fa-history"></i> Logs de Auditoría - {{ $module->name }}
                            </span>
                            <div>
                                <a href="{{ route('superadmin.modules.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-left"></i> Volver a Módulos
                                </a>
                                <a href="{{ route('superadmin.modules.all-logs') }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-list"></i> Todos los Logs
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        @if($logs->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Usuario</th>
                                            <th>Acción</th>
                                            <th>IP</th>
                                            <th>User Agent</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($logs as $log)
                                            <tr>
                                                <td>
                                                    <span class="badge bg-light text-dark">
                                                        {{ $log->timestamp->format('d/m/Y H:i:s') }}
                                                    </span>
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
                                                    <small class="text-muted" title="{{ $log->user_agent }}">
                                                        {{ Str::limit($log->user_agent, 50) }}
                                                    </small>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle"></i>
                                No hay logs de auditoría para este módulo.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('template_title')
    Configuraciones de Backup de Base de Datos
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">
                                <i class="fas fa-database"></i> Configuraciones de Backup de Base de Datos
                            </span>
                            <div class="float-right">
                                <a href="{{ route('superadmin.backup-configs.create') }}" class="btn btn-primary btn-sm">
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

                    <div class="card-body">
                        @if(isset($productionDb))
                            <div class="alert alert-info mb-4">
                                <i class="fas fa-info-circle"></i> 
                                <strong>Base de Datos de Producción:</strong> <code>{{ $productionDb }}</code>
                                <br>
                                <small>No se puede usar esta base de datos como destino de backup.</small>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Host</th>
                                        <th>Base de Datos Destino</th>
                                        <th>Ejecutar Seeders</th>
                                        <th>Último Backup</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($configs as $config)
                                        <tr>
                                            <td>{{ $config->id }}</td>
                                            <td><strong>{{ $config->name }}</strong></td>
                                            <td>{{ $config->host }}:{{ $config->port }}</td>
                                            <td>
                                                <code>{{ $config->database }}</code>
                                                @if($config->isProductionDatabase())
                                                    <span class="badge bg-danger ms-2">PRODUCCIÓN</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($config->execute_seeders)
                                                    <span class="badge bg-success">Sí</span>
                                                @else
                                                    <span class="badge bg-secondary">No</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($config->last_backup_at)
                                                    <small class="text-muted">
                                                        {{ $config->last_backup_at->diffForHumans() }}
                                                        @if($config->last_backup_result)
                                                            @if(($config->last_backup_result['status'] ?? '') === 'success')
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
                                                @if($config->is_active)
                                                    <span class="badge bg-success">Activo</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactivo</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <form action="{{ route('superadmin.backup-configs.execute', $config) }}" 
                                                          method="POST" 
                                                          style="display: inline;"
                                                          onsubmit="return confirm('¿Estás seguro de ejecutar el backup ahora? Este proceso puede tardar varios minutos.');">
                                                        @csrf
                                                        <button type="submit" 
                                                                class="btn btn-sm btn-success" 
                                                                title="Ejecutar Backup"
                                                                {{ $config->isProductionDatabase() ? 'disabled' : '' }}>
                                                            <i class="fas fa-play"></i> Ejecutar
                                                        </button>
                                                    </form>
                                                    
                                                    <a href="{{ route('superadmin.backup-configs.logs', $config) }}" 
                                                       class="btn btn-sm btn-info" 
                                                       title="Ver Logs">
                                                        <i class="fas fa-history"></i>
                                                    </a>
                                                    
                                                    <a href="{{ route('superadmin.backup-configs.edit', $config) }}" 
                                                       class="btn btn-sm btn-primary" 
                                                       title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    
                                                    <form action="{{ route('superadmin.backup-configs.destroy', $config) }}" 
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
                                                <p class="text-muted">No hay configuraciones de backup.</p>
                                                <a href="{{ route('superadmin.backup-configs.create') }}" class="btn btn-primary">
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
@endsection


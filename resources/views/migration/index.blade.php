@extends('layouts.app')

@section('template_title')
    Gestión de Migraciones
@endsection

@push('styles')
    <style>
        .migration-card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            transition: all 0.3s ease;
            margin-bottom: 1rem;
        }
        .migration-card:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        .migration-card .card-header {
            font-weight: bold;
        }
        pre {
            white-space: pre-wrap;
            word-wrap: break-word;
            font-size: 12px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">
                                <i class="fas fa-database"></i> Gestión de Migraciones
                            </span>
                            <a href="{{ route('superadmin.configuraciones.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Volver a Configuraciones
                            </a>
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
                        <div class="row">
                            <!-- Ver Estado -->
                            <div class="col-md-3 mb-3">
                                <div class="card migration-card bg-info text-white">
                                    <div class="card-body text-center">
                                        <i class="fas fa-list fa-2x mb-2"></i>
                                        <h6>Ver Estado</h6>
                                        <p class="small">Ver estado de todas las migraciones</p>
                                        <form method="POST" action="{{ route('superadmin.migrations.execute') }}">
                                            @csrf
                                            <input type="hidden" name="tipo" value="status">
                                            <button type="submit" class="btn btn-light btn-sm">
                                                <i class="fas fa-eye"></i> Ver Estado
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Ejecutar Todas -->
                            <div class="col-md-3 mb-3">
                                <div class="card migration-card bg-success text-white">
                                    <div class="card-body text-center">
                                        <i class="fas fa-play fa-2x mb-2"></i>
                                        <h6>Ejecutar Todas</h6>
                                        <p class="small">Ejecutar migraciones pendientes</p>
                                        <form method="POST" action="{{ route('superadmin.migrations.execute') }}">
                                            @csrf
                                            <input type="hidden" name="tipo" value="run">
                                            <button type="submit" class="btn btn-light btn-sm"
                                                    onclick="return confirm('¿Estás seguro de ejecutar todas las migraciones pendientes?')">
                                                <i class="fas fa-arrow-up"></i> Ejecutar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Ejecutar Configuraciones -->
                            <div class="col-md-3 mb-3">
                                <div class="card migration-card bg-primary text-white">
                                    <div class="card-body text-center">
                                        <i class="fas fa-cog fa-2x mb-2"></i>
                                        <h6>Migración Configuraciones</h6>
                                        <p class="small">Ejecutar solo migración de configuraciones</p>
                                        <form method="POST" action="{{ route('superadmin.migrations.execute') }}">
                                            @csrf
                                            <input type="hidden" name="tipo" value="configuraciones">
                                            <button type="submit" class="btn btn-light btn-sm"
                                                    onclick="return confirm('¿Ejecutar migración de tabla configuraciones?')">
                                                <i class="fas fa-database"></i> Ejecutar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Rollback -->
                            <div class="col-md-3 mb-3">
                                <div class="card migration-card bg-warning text-dark">
                                    <div class="card-body text-center">
                                        <i class="fas fa-undo fa-2x mb-2"></i>
                                        <h6>Rollback</h6>
                                        <p class="small">Revertir última migración</p>
                                        <form method="POST" action="{{ route('superadmin.migrations.execute') }}">
                                            @csrf
                                            <input type="hidden" name="tipo" value="rollback">
                                            <button type="submit" class="btn btn-dark btn-sm"
                                                    onclick="return confirm('⚠️ ¿Estás seguro de hacer rollback? Esto puede perder datos.')">
                                                <i class="fas fa-arrow-down"></i> Rollback
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resultado de la Ejecución -->
        @if (session('resultado'))
            @php $resultado = session('resultado'); @endphp
            <div class="row mt-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-terminal"></i> Resultado de la Ejecución
                                @if($resultado['success'])
                                    <span class="badge bg-success">Éxito</span>
                                @else
                                    <span class="badge bg-danger">Error</span>
                                @endif
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Comando Ejecutado:</h6>
                                    <code>{{ $resultado['comando'] }}</code>

                                    @if(!empty($resultado['opciones']))
                                    <h6 class="mt-3">Opciones:</h6>
                                    <ul>
                                        @foreach($resultado['opciones'] as $key => $value)
                                        <li><code>{{ $key }}: {{ is_array($value) ? json_encode($value) : $value }}</code></li>
                                        @endforeach
                                    </ul>
                                    @endif

                                    <h6 class="mt-3">Código de Salida:</h6>
                                    <span class="badge bg-{{ $resultado['exit_code'] === 0 ? 'success' : 'danger' }}">
                                        {{ $resultado['exit_code'] }}
                                    </span>
                                </div>
                                <div class="col-md-6">
                                    <h6>Salida del Comando:</h6>
                                    <pre class="bg-dark text-light p-3 rounded" style="max-height: 400px; overflow-y: auto;">{{ $resultado['output'] }}</pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Estado Actual de Migraciones -->
        @if($migrationStatus['success'])
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-list"></i> Estado Actual de Migraciones</h3>
                    </div>
                    <div class="card-body">
                        <pre class="bg-dark text-light p-3 rounded" style="max-height: 300px; overflow-y: auto;">{{ $migrationStatus['output'] }}</pre>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Información Adicional -->
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-info-circle"></i> Información Importante</h3>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <h6><i class="fas fa-exclamation-triangle"></i> Notas Importantes:</h6>
                            <ul class="mb-0">
                                <li><strong>Ver Estado:</strong> Muestra todas las migraciones y su estado (ejecutadas/pendientes).</li>
                                <li><strong>Ejecutar Todas:</strong> Aplica todas las migraciones pendientes de forma segura.</li>
                                <li><strong>Migración Configuraciones:</strong> Ejecuta solo la migración de la tabla de configuraciones del sistema.</li>
                                <li><strong>Rollback:</strong> Revierte la última migración ejecutada (¡CUIDADO! Puede perder datos).</li>
                            </ul>
                        </div>

                        <div class="alert alert-warning">
                            <h6><i class="fas fa-shield-alt"></i> Recomendaciones de Seguridad:</h6>
                            <ul class="mb-0">
                                <li>Siempre haz una copia de seguridad antes de ejecutar migraciones en producción.</li>
                                <li>Verifica el estado antes de ejecutar cualquier acción.</li>
                                <li>El rollback puede eliminar datos recientes.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Auto-scroll al resultado si existe
        if ($('.card:contains("Resultado de la Ejecución")').length) {
            $('html, body').animate({
                scrollTop: $('.card:contains("Resultado de la Ejecución")').offset().top - 100
            }, 500);
        }
    });
</script>
@endpush


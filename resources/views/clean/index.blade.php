@extends('layouts.app')

@section('template_title')
    AutoClean - Limpieza del Sistema
@endsection

@push('styles')
    <style>
        .clean-card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            transition: all 0.3s ease;
            margin-bottom: 1rem;
        }
        .clean-card:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transform: translateY(-2px);
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
                                <i class="fas fa-broom"></i> AutoClean - Limpieza del Sistema
                            </span>
                        </div>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show m-4" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                        </div>
                    @endif

                    @if (session('warning'))
                        <div class="alert alert-warning alert-dismissible fade show m-4" role="alert">
                            {{ session('warning') }}
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
                            <!-- Limpiar Cache -->
                            <div class="col-md-4 mb-3">
                                <div class="card clean-card bg-info text-white">
                                    <div class="card-body text-center">
                                        <i class="fas fa-database fa-2x mb-2"></i>
                                        <h6>Limpiar Cache</h6>
                                        <p class="small">php artisan cache:clear</p>
                                        <form method="POST" action="{{ route('superadmin.clean.execute') }}">
                                            @csrf
                                            <input type="hidden" name="tipo" value="cache">
                                            <button type="submit" class="btn btn-light btn-sm">
                                                <i class="fas fa-broom"></i> Limpiar Cache
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Limpiar Configuración -->
                            <div class="col-md-4 mb-3">
                                <div class="card clean-card bg-primary text-white">
                                    <div class="card-body text-center">
                                        <i class="fas fa-cog fa-2x mb-2"></i>
                                        <h6>Limpiar Configuración</h6>
                                        <p class="small">php artisan config:clear</p>
                                        <form method="POST" action="{{ route('superadmin.clean.execute') }}">
                                            @csrf
                                            <input type="hidden" name="tipo" value="config">
                                            <button type="submit" class="btn btn-light btn-sm">
                                                <i class="fas fa-broom"></i> Limpiar Config
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Limpiar Rutas -->
                            <div class="col-md-4 mb-3">
                                <div class="card clean-card bg-success text-white">
                                    <div class="card-body text-center">
                                        <i class="fas fa-route fa-2x mb-2"></i>
                                        <h6>Limpiar Rutas</h6>
                                        <p class="small">php artisan route:clear</p>
                                        <form method="POST" action="{{ route('superadmin.clean.execute') }}">
                                            @csrf
                                            <input type="hidden" name="tipo" value="route">
                                            <button type="submit" class="btn btn-light btn-sm">
                                                <i class="fas fa-broom"></i> Limpiar Rutas
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Limpiar Vistas -->
                            <div class="col-md-4 mb-3">
                                <div class="card clean-card bg-warning text-dark">
                                    <div class="card-body text-center">
                                        <i class="fas fa-eye fa-2x mb-2"></i>
                                        <h6>Limpiar Vistas</h6>
                                        <p class="small">php artisan view:clear</p>
                                        <form method="POST" action="{{ route('superadmin.clean.execute') }}">
                                            @csrf
                                            <input type="hidden" name="tipo" value="view">
                                            <button type="submit" class="btn btn-dark btn-sm">
                                                <i class="fas fa-broom"></i> Limpiar Vistas
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Limpiar Compilados -->
                            <div class="col-md-4 mb-3">
                                <div class="card clean-card bg-secondary text-white">
                                    <div class="card-body text-center">
                                        <i class="fas fa-file-code fa-2x mb-2"></i>
                                        <h6>Limpiar Compilados</h6>
                                        <p class="small">php artisan clear-compiled</p>
                                        <form method="POST" action="{{ route('superadmin.clean.execute') }}">
                                            @csrf
                                            <input type="hidden" name="tipo" value="compiled">
                                            <button type="submit" class="btn btn-light btn-sm">
                                                <i class="fas fa-broom"></i> Limpiar Compilados
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Actualizar Autoload -->
                            <div class="col-md-4 mb-3">
                                <div class="card clean-card bg-dark text-white">
                                    <div class="card-body text-center">
                                        <i class="fas fa-sync fa-2x mb-2"></i>
                                        <h6>Actualizar Autoload</h6>
                                        <p class="small">composer dump-autoload</p>
                                        <form method="POST" action="{{ route('superadmin.clean.execute') }}">
                                            @csrf
                                            <input type="hidden" name="tipo" value="autoload">
                                            <button type="submit" class="btn btn-light btn-sm">
                                                <i class="fas fa-sync"></i> Actualizar Autoload
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botón Limpiar Todo -->
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="card clean-card bg-danger text-white">
                                    <div class="card-body text-center">
                                        <i class="fas fa-magic fa-3x mb-2"></i>
                                        <h4>Limpieza Completa</h4>
                                        <p class="mb-3">Ejecuta todos los comandos de limpieza de una vez</p>
                                        <form method="POST" action="{{ route('superadmin.clean.execute') }}">
                                            @csrf
                                            <input type="hidden" name="tipo" value="all">
                                            <button type="submit" class="btn btn-light btn-lg"
                                                    onclick="return confirm('⚠️ ¿Estás seguro de ejecutar TODOS los comandos de limpieza? Esto puede tomar unos momentos.')">
                                                <i class="fas fa-magic"></i> Limpiar Todo
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

        <!-- Resultados de la Ejecución -->
        @if (session('resultados'))
            @php $resultados = session('resultados'); @endphp
            <div class="row mt-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-terminal"></i> Resultados de la Ejecución
                                @php
                                    $todosExitosos = collect($resultados)->every(function($resultado) {
                                        return $resultado['success'];
                                    });
                                @endphp
                                @if($todosExitosos)
                                    <span class="badge bg-success">Todos Exitosos</span>
                                @else
                                    <span class="badge bg-warning">Algunos con Errores</span>
                                @endif
                            </h3>
                        </div>
                        <div class="card-body">
                            @foreach($resultados as $index => $resultado)
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6>
                                            <i class="fas fa-{{ $resultado['tipo'] === 'composer' ? 'box' : 'terminal' }}"></i>
                                            {{ $resultado['descripcion'] }}
                                        </h6>
                                        <span class="badge bg-{{ $resultado['success'] ? 'success' : 'danger' }}">
                                            {{ $resultado['success'] ? 'Éxito' : 'Error' }}
                                        </span>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Comando:</strong>
                                            <code>{{ $resultado['comando'] }}</code>
                                            <br>
                                            <strong>Código de Salida:</strong>
                                            <span class="badge bg-{{ $resultado['exit_code'] === 0 ? 'success' : 'danger' }}">
                                                {{ $resultado['exit_code'] }}
                                            </span>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Salida:</strong>
                                            <pre class="bg-dark text-light p-3 rounded" style="max-height: 200px; overflow-y: auto; font-size: 11px;">{{ $resultado['output'] ?: '(Sin salida)' }}</pre>
                                        </div>
                                    </div>
                                    @if(!$loop->last)
                                        <hr>
                                    @endif
                                </div>
                            @endforeach
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
                            <h6><i class="fas fa-lightbulb"></i> ¿Qué hace cada comando?</h6>
                            <ul class="mb-0">
                                <li><strong>Limpiar Cache:</strong> Elimina todos los datos almacenados en cache de la aplicación.</li>
                                <li><strong>Limpiar Configuración:</strong> Elimina el cache de configuración de Laravel.</li>
                                <li><strong>Limpiar Rutas:</strong> Elimina el cache de rutas y regenera el archivo de rutas.</li>
                                <li><strong>Limpiar Vistas:</strong> Elimina las vistas compiladas (blade templates compilados).</li>
                                <li><strong>Limpiar Compilados:</strong> Elimina los archivos de clases compiladas optimizadas.</li>
                                <li><strong>Actualizar Autoload:</strong> Regenera el autoload de Composer (útil después de agregar clases nuevas).</li>
                            </ul>
                        </div>

                        <div class="alert alert-warning">
                            <h6><i class="fas fa-exclamation-triangle"></i> Recomendaciones:</h6>
                            <ul class="mb-0">
                                <li>Ejecuta estos comandos cuando tengas problemas con cache o después de cambios importantes.</li>
                                <li>La "Limpieza Completa" es útil después de actualizaciones o cambios de configuración.</li>
                                <li>Estos comandos son seguros y no afectan los datos de la base de datos.</li>
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
        if ($('.card:contains("Resultados de la Ejecución")').length) {
            $('html, body').animate({
                scrollTop: $('.card:contains("Resultados de la Ejecución")').offset().top - 100
            }, 500);
        }
    });
</script>
@endpush


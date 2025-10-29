@extends('layouts.app')

@section('template_title')
    Configuraciones del Sistema
@endsection

@push('styles')
    <style>
        .config-card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .config-card:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .config-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            border-radius: 8px 8px 0 0;
        }
        .timeout-display {
            font-size: 2rem;
            font-weight: bold;
            color: #667eea;
        }
        .timeout-unit {
            font-size: 1rem;
            color: #6c757d;
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
                                <i class="fas fa-cog"></i> Configuraciones del Sistema
                            </span>
                            <div>
                                <a href="{{ route('superadmin.migrations.index') }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-database"></i> Gestión de Migraciones
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
                        @foreach($configuraciones as $categoria => $configs)
                            <div class="mb-4">
                                <h4 class="mb-3">
                                    <i class="fas fa-folder"></i> {{ ucfirst($categoria) }}
                                </h4>

                                <div class="row">
                                    @foreach($configs as $config)
                                        <div class="col-md-6 mb-3">
                                            <div class="card config-card">
                                                @if($config->clave == 'session_timeout')
                                                    <div class="config-header">
                                                        <h5 class="mb-0">
                                                            <i class="fas fa-clock"></i> {{ $config->descripcion ?? 'Timeout de Sesión' }}
                                                        </h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="text-center mb-3">
                                                            <div class="timeout-display">
                                                                {{ round($config->valor / 60) }}
                                                                <span class="timeout-unit">minutos</span>
                                                            </div>
                                                            <small class="text-muted">({{ number_format($config->valor) }} segundos)</small>
                                                        </div>

                                                        <form action="{{ route('configuraciones.update-session-timeout') }}" method="POST" id="form-timeout-{{ $config->id }}">
                                                            @csrf
                                                            <div class="mb-3">
                                                                <label for="session_timeout" class="form-label">
                                                                    <i class="fas fa-hourglass-half"></i> Tiempo de Sesión (en segundos)
                                                                </label>
                                                                <div class="input-group">
                                                                    <input
                                                                        type="number"
                                                                        class="form-control"
                                                                        id="session_timeout"
                                                                        name="session_timeout"
                                                                        value="{{ $config->valor }}"
                                                                        min="60"
                                                                        max="14400"
                                                                        step="60"
                                                                        required
                                                                    >
                                                                    <span class="input-group-text">seg</span>
                                                                    <button type="button" class="btn btn-outline-secondary" onclick="convertirAMinutos({{ $config->id }})">
                                                                        <i class="fas fa-calculator"></i> Minutos
                                                                    </button>
                                                                </div>
                                                                <div class="form-text">
                                                                    <strong>Conversión rápida:</strong>
                                                                    <span id="minutos-display-{{ $config->id }}">{{ round($config->valor / 60) }}</span> minutos
                                                                    (Mín: 1 min, Máx: 240 min)
                                                                </div>
                                                            </div>

                                                            <div class="d-grid gap-2">
                                                                <button type="submit" class="btn btn-primary">
                                                                    <i class="fas fa-save"></i> Guardar Cambios
                                                                </button>
                                                                <a href="{{ route('configuraciones.edit', $config->id) }}" class="btn btn-outline-secondary btn-sm">
                                                                    <i class="fas fa-edit"></i> Editar Detalles
                                                                </a>
                                                            </div>
                                                        </form>
                                                    </div>
                                                @else
                                                    <div class="card-body">
                                                        <h6 class="card-title">
                                                            <i class="fas fa-setting"></i> {{ $config->descripcion ?? $config->clave }}
                                                        </h6>
                                                        <p class="card-text">
                                                            <strong>Valor actual:</strong>
                                                            <code>{{ $config->valor }}</code>
                                                        </p>
                                                        <p class="card-text text-muted small">
                                                            Clave: <code>{{ $config->clave }}</code>
                                                        </p>
                                                        <a href="{{ route('configuraciones.edit', $config->id) }}" class="btn btn-primary btn-sm">
                                                            <i class="fas fa-edit"></i> Editar
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Actualizar minutos cuando cambia el valor
    document.querySelectorAll('input[name="session_timeout"]').forEach(input => {
        input.addEventListener('input', function() {
            const segundos = parseInt(this.value) || 0;
            const minutos = Math.round(segundos / 60);
            const configId = this.closest('form').id.split('-')[2];
            document.getElementById('minutos-display-' + configId).textContent = minutos;
        });
    });

    // Conversión rápida de minutos a segundos
    function convertirAMinutos(configId) {
        const input = document.querySelector(`#form-timeout-${configId} input[name="session_timeout"]`);
        const minutos = prompt(`Ingrese el tiempo en minutos (mínimo 1, máximo 240):`, Math.round(input.value / 60));

        if (minutos !== null && !isNaN(minutos)) {
            const minutosInt = parseInt(minutos);
            if (minutosInt >= 1 && minutosInt <= 240) {
                input.value = minutosInt * 60;
                document.getElementById('minutos-display-' + configId).textContent = minutosInt;
            } else {
                alert('El valor debe estar entre 1 y 240 minutos');
            }
        }
    }
</script>
@endpush


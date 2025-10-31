@extends('layouts.app')

@section('template_title')
    Administrador de Módulos
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">
                                <i class="fas fa-puzzle-piece"></i> Módulos del Sistema
                            </span>
                            <div>
                                <a href="{{ route('superadmin.modules.all-logs') }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-history"></i> Ver Todos los Logs
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

                    <div class="card-body">
                        <form method="GET" class="row g-2 mb-3">
                            <div class="col-md-4">
                                <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Buscar por nombre, slug o descripción">
                            </div>
                            <div class="col-md-3">
                                <select name="status" class="form-select">
                                    <option value="">Todos</option>
                                    <option value="active" {{ request('status')==='active' ? 'selected' : '' }}>Activos</option>
                                    <option value="inactive" {{ request('status')==='inactive' ? 'selected' : '' }}>Inactivos</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-primary" type="submit"><i class="fas fa-filter"></i> Filtrar</button>
                                <a href="{{ route('superadmin.modules.index') }}" class="btn btn-secondary">Limpiar</a>
                            </div>
                        </form>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Slug</th>
                                        <th>Descripción</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                        <th>Logs</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($modules as $module)
                                        <tr>
                                            <td>{{ $module->name }}</td>
                                            <td><code>{{ $module->slug }}</code></td>
                                            <td>{{ $module->description }}</td>
                                            <td>
                                                @if ($module->status)
                                                    <span class="badge bg-success">Activo</span>
                                                @else
                                                    <span class="badge bg-danger">Inactivo</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="module-toggle-wrapper" data-module-id="{{ $module->id }}" data-module-slug="{{ $module->slug }}">
                                                    @if($module->status)
                                                        <button type="button" class="btn btn-sm btn-danger toggle-module-btn" data-action="desactivar">
                                                            <i class="fas fa-ban"></i> Desactivar
                                                        </button>
                                                    @else
                                                        <button type="button" class="btn btn-sm btn-success toggle-module-btn" data-action="activar">
                                                            <i class="fas fa-check"></i> Activar
                                                        </button>
                                                    @endif
                                                    <div class="verification-form mt-2" style="display: none;">
                                                        <input type="text" class="form-control form-control-sm d-inline-block" style="max-width: 140px;" placeholder="Código" maxlength="6" id="code_{{ $module->id }}">
                                                        <button type="button" class="btn btn-sm btn-primary confirm-code-btn" data-module-id="{{ $module->id }}">
                                                            <i class="fas fa-check"></i> Confirmar
                                                        </button>
                                                    </div>
                                                    <div class="message mt-2"></div>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{ route('superadmin.modules.logs', $module) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-history"></i> Ver Logs
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-3">
                                {{ $modules->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Manejar clic en botones de toggle
    document.querySelectorAll('.toggle-module-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const wrapper = this.closest('.module-toggle-wrapper');
            const moduleId = wrapper.dataset.moduleId;
            const action = this.dataset.action;

            // Solicitar código de verificación
            fetch(`/superadmin/modules/${moduleId}/request-toggle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ action: action })
            })
            .then(response => response.json())
            .then(data => {
                if (data.ok || data.message) {
                    wrapper.querySelector('.verification-form').style.display = 'block';
                    wrapper.querySelector('.message').innerHTML = '<div class="alert alert-info py-1 px-2 mb-0">Se envió un código a tu correo. Ingresa el código para confirmar.</div>';
                    this.style.display = 'none';
                } else {
                    wrapper.querySelector('.message').innerHTML = '<div class="alert alert-danger py-1 px-2 mb-0">Error: ' + (data.message || 'Error desconocido') + '</div>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                wrapper.querySelector('.message').innerHTML = '<div class="alert alert-danger py-1 px-2 mb-0">Error de conexión. Intenta de nuevo.</div>';
            });
        });
    });

    // Manejar confirmación de código
    document.querySelectorAll('.confirm-code-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const moduleId = this.dataset.moduleId;
            const wrapper = this.closest('.module-toggle-wrapper');
            const codeInput = document.getElementById('code_' + moduleId);
            const code = codeInput.value.trim();

            if (code.length !== 6) {
                wrapper.querySelector('.message').innerHTML = '<div class="alert alert-warning py-1 px-2 mb-0">El código debe tener 6 dígitos.</div>';
                return;
            }

            // Confirmar cambio de estado
            fetch(`/superadmin/modules/${moduleId}/confirm`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ verification_code: code })
            })
            .then(response => response.json())
            .then(data => {
                if (data.ok) {
                    wrapper.querySelector('.message').innerHTML = '<div class="alert alert-success py-1 px-2 mb-0">' + data.message + '</div>';
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    wrapper.querySelector('.message').innerHTML = '<div class="alert alert-danger py-1 px-2 mb-0">' + (data.message || 'Código inválido o expirado') + '</div>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                wrapper.querySelector('.message').innerHTML = '<div class="alert alert-danger py-1 px-2 mb-0">Error de conexión. Intenta de nuevo.</div>';
            });
        });
    });
});
</script>
@endpush




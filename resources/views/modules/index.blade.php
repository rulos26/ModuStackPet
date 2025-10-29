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
                                                <livewire:modules.toggle-button :module="$module" />
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




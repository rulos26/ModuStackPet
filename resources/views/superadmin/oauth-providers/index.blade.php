@extends('layouts.app')

@section('template_title')
    Proveedores OAuth
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">
                                <i class="fas fa-key"></i> Gestión de Proveedores OAuth
                            </span>
                            <div class="float-right">
                                <a href="{{ route('superadmin.oauth-providers.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Nuevo Proveedor
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

                    <div class="alert alert-info m-4">
                        <h5 class="mb-2"><i class="fas fa-info-circle"></i> Módulo Multi-Provider</h5>
                        <p class="mb-2"><strong>Puedes configurar MÚLTIPLES proveedores OAuth simultáneamente:</strong></p>
                        <ul class="mb-0 small">
                            <li>✅ Configura Google, Facebook, GitHub, Twitter, LinkedIn y más <strong>al mismo tiempo</strong></li>
                            <li>✅ Cada provider tiene credenciales independientes</li>
                            <li>✅ Activa/desactiva cada uno individualmente</li>
                            <li>✅ Los botones aparecerán automáticamente en login/register solo para los activos</li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Provider</th>
                                        <th>Client ID</th>
                                        <th>Redirect URI</th>
                                        <th>Estado</th>
                                        <th>Configurado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($providers as $provider)
                                        <tr>
                                            <td>{{ $provider->id }}</td>
                                            <td><strong>{{ $provider->name }}</strong></td>
                                            <td><code>{{ $provider->provider }}</code></td>
                                            <td>
                                                @if($provider->client_id)
                                                    <span class="badge bg-success">Configurado</span>
                                                @else
                                                    <span class="badge bg-warning">Pendiente</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($provider->redirect_uri)
                                                    <small class="text-muted">{{ Str::limit($provider->redirect_uri, 40) }}</small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($provider->is_active)
                                                    <span class="badge bg-success">Activo</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactivo</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($provider->isConfigured())
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check-circle"></i> Completo
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-times-circle"></i> Incompleto
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('superadmin.oauth-providers.edit', $provider) }}" 
                                                       class="btn btn-sm btn-primary" 
                                                       title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    
                                                    <form action="{{ route('superadmin.oauth-providers.toggle-status', $provider) }}" 
                                                          method="POST" 
                                                          style="display: inline;">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" 
                                                                class="btn btn-sm {{ $provider->is_active ? 'btn-warning' : 'btn-success' }}" 
                                                                title="{{ $provider->is_active ? 'Desactivar' : 'Activar' }}">
                                                            <i class="fas {{ $provider->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                                        </button>
                                                    </form>
                                                    
                                                    <form action="{{ route('superadmin.oauth-providers.destroy', $provider) }}" 
                                                          method="POST" 
                                                          style="display: inline;"
                                                          onsubmit="return confirm('¿Estás seguro de eliminar este proveedor?');">
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
                                                <p class="text-muted">No hay proveedores OAuth configurados.</p>
                                                <a href="{{ route('superadmin.oauth-providers.create') }}" class="btn btn-primary">
                                                    <i class="fas fa-plus"></i> Crear Primer Proveedor
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


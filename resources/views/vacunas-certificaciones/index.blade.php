@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <!-- Tarjeta principal -->
            <div class="card">
                <!-- Encabezado de la tarjeta -->
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">{{ __('Gestión de Vacunas y Certificaciones') }}</h3>
                        <a href="{{ route('vacunas-certificaciones.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> {{ __('Crear Nuevo') }}
                        </a>
                    </div>
                </div>

                <!-- Cuerpo de la tarjeta -->
                <div class="card-body">
                    <!-- Mensaje de éxito -->
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Tabla de registros -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('Mascota') }}</th>
                                    <th>{{ __('Fecha Última Vacuna') }}</th>
                                    <th>{{ __('Operaciones') }}</th>
                                    <th>{{ __('Certificado Veterinario') }}</th>
                                    <th>{{ __('Cédula Propietario') }}</th>
                                    <th>{{ __('Acciones') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($vacunas as $vacuna)
                                    <tr>
                                        <!-- Nombre de la mascota -->
                                        <td>{{ $vacuna->mascota->nombre }}</td>

                                        <!-- Fecha de última vacuna formateada -->
                                        <td>{{ $vacuna->fecha_ultima_vacuna_formateada ?? 'No especificada' }}</td>

                                        <!-- Operaciones realizadas -->
                                        <td>{{ $vacuna->operaciones ?? 'No especificadas' }}</td>

                                        <!-- Enlace al certificado veterinario -->
                                        <td>
                                            @if($vacuna->certificado_veterinario)
                                                <a href="{{ $vacuna->certificado_veterinario_url }}" target="_blank" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> {{ __('Ver') }}
                                                </a>
                                            @else
                                                {{ __('No disponible') }}
                                            @endif
                                        </td>

                                        <!-- Enlace a la cédula del propietario -->
                                        <td>
                                            @if($vacuna->cedula_propietario)
                                                <a href="{{ $vacuna->cedula_propietario_url }}" target="_blank" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> {{ __('Ver') }}
                                                </a>
                                            @else
                                                {{ __('No disponible') }}
                                            @endif
                                        </td>

                                        <!-- Botones de acción -->
                                        <td>
                                            <div class="btn-group" role="group">
                                                <!-- Botón de ver detalles -->
                                                <a href="{{ route('vacunas-certificaciones.show', $vacuna) }}" class="btn btn-sm btn-info" title="{{ __('Ver detalles') }}">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                <!-- Botón de editar -->
                                                <a href="{{ route('vacunas-certificaciones.edit', $vacuna) }}" class="btn btn-sm btn-primary" title="{{ __('Editar') }}">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <!-- Formulario de eliminación -->
                                                <form action="{{ route('vacunas-certificaciones.destroy', $vacuna) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('{{ __('¿Estás seguro de eliminar este registro?') }}')" title="{{ __('Eliminar') }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <!-- Mensaje cuando no hay registros -->
                                    <tr>
                                        <td colspan="6" class="text-center">{{ __('No hay registros de vacunas y certificaciones.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $vacunas->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

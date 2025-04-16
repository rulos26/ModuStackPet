@extends('layouts.app')

@section('template_title')
    {{ __('Rutas de Documentos') }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">
                                {{ __('Rutas de Documentos') }}
                            </span>
                            <div class="float-right">
                                <a href="{{ route('paths-documentos.create') }}" class="btn btn-primary btn-sm float-right" data-placement="left">
                                    <i class="fas fa-plus"></i> {{ __('Nueva Ruta') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="pathsTable">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>
                                        <th>Nombre de la Ruta</th>
                                        <th>Estado</th>
                                        <th>Fecha de Creación</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($paths as $path)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $path->nombre_path }}</td>
                                            <td>
                                                @if($path->estado)
                                                    <span class="badge badge-success">Activo</span>
                                                @else
                                                    <span class="badge badge-danger">Inactivo</span>
                                                @endif
                                            </td>
                                            <td>{{ $path->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <form action="{{ route('paths-documentos.destroy', $path->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-info" href="{{ route('paths-documentos.show', $path->id) }}">
                                                        <i class="fas fa-eye"></i> {{ __('Ver') }}
                                                    </a>
                                                    <a class="btn btn-sm btn-primary" href="{{ route('paths-documentos.edit', $path->id) }}">
                                                        <i class="fas fa-edit"></i> {{ __('Editar') }}
                                                    </a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de eliminar esta ruta?');">
                                                        <i class="fas fa-trash"></i> {{ __('Eliminar') }}
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page_css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">
@endpush

@push('page_scripts')
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#pathsTable').DataTable({
                responsive: true,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
                },
                order: [[3, 'desc']], // Ordenar por fecha de creación descendente
                pageLength: 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todos"]]
            });
        });
    </script>
@endpush

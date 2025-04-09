@extends('layouts.app')

@section('template_title')
    Asignar Roles
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <span id="card_title">
                                {{ __('Asignar Roles a Usuarios') }}
                            </span>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success m-4">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    <div class="card-body bg-white">
                        <div class="table-responsive">
                            <table id="rolesTable" class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>
                                        <th>Usuario</th>
                                        <th>Email</th>
                                        <th>Roles actuales</th>
                                        <th>Acceso rápido</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($usuarios as $index => $usuario)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $usuario->name }}</td>
                                            <td>{{ $usuario->email }}</td>
                                            <td>{{ $usuario->roles->pluck('name')->join(', ') }}</td>
                                            <td>
                                                @if (!$usuario->roles->pluck('name')->intersect(['Superadmin', 'Admin'])->isNotEmpty())
                                                    <form action="{{ route('usuarios.roles.asignar', $usuario) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <input type="hidden" name="rol" value="Cliente">
                                                        <button type="submit" class="btn btn-primary btn-sm mb-1 w-100">Cliente</button>
                                                    </form>

                                                    <form action="{{ route('usuarios.roles.asignar', $usuario) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <input type="hidden" name="rol" value="Paseador">
                                                        <button type="submit" class="btn btn-success btn-sm w-100">Paseador</button>
                                                    </form>
                                                @else
                                                    <span class="text-muted">No se pueden modificar roles</span>
                                                @endif
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

@section('js')
    <!-- DataTables Script -->
    <script>
        $(document).ready(function() {
            $('#rolesTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json" // Traducción al español
                },
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true // Hacer la tabla responsiva
            });
        });
    </script>
@endsection

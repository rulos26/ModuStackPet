@extends('layouts.app')

@section('template_title')
    Gestión de Vacunas y Certificaciones
@endsection

@push('styles')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">
                                <i class="fas fa-syringe"></i> Vacunas y Certificaciones
                            </span>
                            @hasanyrole('Superadmin|Admin')
                            <div class="float-right">
                                <a href="{{ route('vacunas_certificaciones.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Nuevo Registro
                                </a>
                            </div>
                            @endhasanyrole
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
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="vacunasTable">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No.</th>
                                        <th>Mascota</th>
                                        <th>Última Vacuna</th>
                                        <th>Operaciones</th>
                                        <th>Certificado</th>
                                        <th>Cédula Propietario</th>
                                        <th>Fecha Creación</th>
                                        <th class="text-center no-sort">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($vacunasCertificaciones as $vacuna)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $vacuna->mascota->nombre }}</td>
                                            <td>{{ $vacuna->fecha_ultima_vacuna->format('d/m/Y') }}</td>
                                            <td>{{ $vacuna->operaciones ?? 'N/A' }}</td>
                                            <td>
                                                @if($vacuna->certificado_veterinario)
                                                    <a href="{{ asset('storage/' . $vacuna->certificado_veterinario) }}" target="_blank" class="btn btn-info btn-sm">
                                                        <i class="fas fa-file-pdf"></i> Ver
                                                    </a>
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>{{ $vacuna->cedula_propietario ?? 'N/A' }}</td>
                                            <td>{{ $vacuna->created_at->format('d/m/Y H:i:s') }}</td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group" aria-label="Acciones">
                                                    @hasanyrole('Superadmin|Admin')
                                                    <a class="btn btn-info btn-sm" href="{{ route('vacunas_certificaciones.show', $vacuna->id) }}" title="Ver">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a class="btn btn-success btn-sm" href="{{ route('vacunas_certificaciones.edit', $vacuna->id) }}" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    @endhasanyrole

                                                    @hasrole('Superadmin')
                                                    <form action="{{ route('vacunas_certificaciones.destroy', $vacuna->id) }}" method="POST" class="d-inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                    @endhasrole
                                                </div>
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

@push('scripts')
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#vacunasTable').DataTable({
                responsive: true,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'collection',
                        text: '<i class="fas fa-download"></i> Exportar',
                        buttons: [
                            {
                                extend: 'excel',
                                text: '<i class="fas fa-file-excel"></i> Excel',
                                exportOptions: {
                                    columns: [0,1,2,3,5,6]
                                }
                            },
                            {
                                extend: 'pdf',
                                text: '<i class="fas fa-file-pdf"></i> PDF',
                                exportOptions: {
                                    columns: [0,1,2,3,5,6]
                                }
                            },
                            {
                                extend: 'print',
                                text: '<i class="fas fa-print"></i> Imprimir',
                                exportOptions: {
                                    columns: [0,1,2,3,5,6]
                                }
                            }
                        ]
                    },
                    {
                        extend: 'colvis',
                        text: '<i class="fas fa-columns"></i> Columnas'
                    }
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                columnDefs: [
                    {
                        targets: 'no-sort',
                        orderable: false
                    }
                ],
                order: [[1, 'asc']]
            });

            // Confirmación de eliminación con SweetAlert2
            $(document).on('submit', '.delete-form', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: '¿Está seguro?',
                    text: "¡No podrá revertir esta acción!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });
    </script>
@endpush

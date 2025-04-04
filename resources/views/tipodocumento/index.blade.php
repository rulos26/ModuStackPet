@extends('adminlte::page') @section('content_header')
    <h1 class="m-0 text-dark"></h1>
    @stop


    @section('content')
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div style="display: flex; justify-content: space-between; align-items: center;">

                                <span id="card_title">
                                    <i class="fas fa-solid fa-business-time">  Tipo Documentos</i>
                                </span>

                                <div class="float-right">
                                    <a href="{{ route('tipodocumento.create') }}" class="btn btn-primary btn-sm float-right"
                                        data-placement="left">
                                        {{ __('Nuevo ') }}
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
                                <table id="example" class="table table-striped table-hover">
                                    <thead class="thead">
                                        <tr>
                                            @if ($superadmin == 0)
                                                <th>No</th>
                                                <th>Nombre</th>
                                                <th></th>
                                                <th></th>
                                            @else
                                                <th>No</th>
                                                <th>Nombre</th>
                                                <th></th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tipoDocumentos as $tipoDocumentos)
                                            <tr>
                                                <td>{{ ++$i }}</td>

                                                <td>{{ $tipoDocumentos->nombre }}</td>


                                                @if ($superadmin == 0)
                                                    <td>
                                                        @can('tipos-cuenta.show')
                                                            <a class="btn btn-sm btn-primary "
                                                                href="{{ route('tipodocumento.show', $tipoDocumentos->id) }}"><i
                                                                    class="fa fa-fw fa-eye"></i> {{ __('Ver') }}</a>
                                                        @endcan
                                                    </td>
                                                @else
                                                @endif
                                                <td>

                                                    @if ($superadmin == 1)
                                                        @include('AdminDashboard.tipodocumento.menu.menu')
                                                    @else
                                                        @include('AdminDashboard.tipodocumento.modal.token')
                                                        <button type="button" class="btn btn-info btn-sm"
                                                            data-toggle="modal" data-target="#miVentanaModal">
                                                            @if (Auth::user()->id == 1)
                                                                <i class="fa fa-fw fa-key"></i>
                                                                {{ __('Activar SuperAdministrador') }}
                                                            @else
                                                                <i class="fa fa-fw fa-key"></i>
                                                                {{ __('Activar Administrador') }}
                                                            @endif
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
    @section('footer')
        <!-- Contenido del footer para la página de inicio -->

    @endsection
    @push('scripts')
        <!-- Agrega estos enlaces en tu archivo de diseño o en la sección de encabezado de tu vista -->
        <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
        <script>
            $('#example').DataTable({
                language: {
                    "search": "Buscar",
                    "lengthMenu": "Mostrar _MENU_ registros por pagina",
                    "info": "Mostrado pagina _PAGE_ de _PAGES_",
                    "paginate": {
                        "previous": "Anterior",
                        "next": "siguiente",
                        "first": "primero",
                        "last": "ultimo"
                    }
                },

            });
        </script>
    @endpush

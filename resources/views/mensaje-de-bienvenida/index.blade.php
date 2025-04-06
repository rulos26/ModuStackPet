@extends('layouts.app')

@section('template_title')
    Mensajes de Bienvenida
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <!-- Título de la tarjeta y botón para crear un nuevo mensaje -->
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">
                                {{ __('Mensajes de Bienvenida') }}
                            </span>

                            <div class="float-right">
                                <!-- Botón para crear un nuevo mensaje -->
                                <a href="{{ route('mensaje-de-bienvenidas.create') }}" class="btn btn-primary btn-sm float-right" data-placement="left">
                                    {{ __('Crear Nuevo') }}
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Mostrar mensaje de éxito si existe -->
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body bg-white">
                        <!-- Tabla para mostrar los mensajes de bienvenida -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>
                                        <th>Título</th>
                                        <th>Descripción</th>
                                        <th>Logo</th>
                                        <th>Rol</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Iterar sobre los mensajes de bienvenida -->
                                    @foreach ($mensajeDeBienvenidas as $mensajeDeBienvenida)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $mensajeDeBienvenida->titulo }}</td>
                                            <td>{{ $mensajeDeBienvenida->descripcion }}</td>
                                            <td>
                                                <!-- Mostrar el logo si existe -->
                                                @if ($mensajeDeBienvenida->logo)
                                                    <img src="{{ asset('storage/' . $mensajeDeBienvenida->logo) }}" alt="Logo" style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    Sin logo
                                                @endif
                                            </td>
                                            <td>{{ $mensajeDeBienvenida->rol }}</td>
                                            <td>
                                                <!-- Botones de acción: Ver, Editar, Eliminar -->
                                                <form action="{{ route('mensaje-de-bienvenidas.destroy', $mensajeDeBienvenida->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary" href="{{ route('mensaje-de-bienvenidas.show', $mensajeDeBienvenida->id) }}">
                                                        <i class="fa fa-fw fa-eye"></i> {{ __('Ver') }}
                                                    </a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('mensaje-de-bienvenidas.edit', $mensajeDeBienvenida->id) }}">
                                                        <i class="fa fa-fw fa-edit"></i> {{ __('Editar') }}
                                                    </a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('¿Estás seguro de eliminar este mensaje?') ? this.closest('form').submit() : false;">
                                                        <i class="fa fa-fw fa-trash"></i> {{ __('Eliminar') }}
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
                <!-- Paginación -->
                {!! $mensajeDeBienvenidas->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('template_title')
    Usuarios
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">

                            <span id="card_title">
                                {{ __('Usuarios') }}
                            </span>

                             <div>
                                <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm" data-placement="left">
                                  {{ __('Crear Nuevo') }}
                                </a>
                              </div>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>
                                        <th>Nombre</th>
                                        <th>Correo Electrónico</th>
                                        <th>Tipo de Documento</th>
                                        <th>Cédula</th>
                                        <th>Avatar</th>
                                        <th>Activo</th>
                                        <th>Teléfono</th>
                                        <th>WhatsApp</th>
                                        <th>Fecha de Nacimiento</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->tipo_documento }}</td>
                                            <td>{{ $user->cedula }}</td>
                                            <td>
                                                @if ($user->avatar)
                                                    <img src="{{ asset('public/' . $user->avatar) }}" alt="Avatar" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <img src="{{ asset('public/storage/img/default.png') }}" alt="Avatar" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                                @endif
                                            </td>
                                            <td>{{ $user->activo ? __('Sí') : __('No') }}</td>
                                            <td>{{ $user->telefono }}</td>
                                            <td>{{ $user->whatsapp }}</td>
                                            <td>{{ $user->fecha_nacimiento }}</td>
                                            <td>
                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary" href="{{ route('users.show', $user->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Ver') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('users.edit', $user->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Editar') }}</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('¿Estás seguro de eliminar?') ? this.closest('form').submit() : false;"><i class="fa fa-fw fa-trash"></i> {{ __('Eliminar') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center">
                    {!! $users->withQueryString()->links() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

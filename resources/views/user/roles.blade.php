@extends('layouts.app')

@section('title', 'Asignar Roles')

@section('content')
<div class="container mt-4">
    <h3>Asignar Roles a Usuarios</h3>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered table-hover mt-3">
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Email</th>
                <th>Roles actuales</th>
                <th>Acceso rápido</th>
            </tr>
        </thead>
        <tbody>
            @foreach($usuarios as $usuario)
                <tr>
                    <td>{{ $usuario->name }}</td>
                    <td>{{ $usuario->email }}</td>
                    <td>{{ $usuario->roles->pluck('name')->join(', ') }}</td>
                    <td>
                        <form action="{{ route('usuarios.roles.asignar', $usuario) }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="rol" value="Cliente">
                            <button type="submit" class="btn btn-outline-primary btn-sm">Cliente</button>
                        </form>

                        <form action="{{ route('usuarios.roles.asignar', $usuario) }}" method="POST" class="d-inline ms-2">
                            @csrf
                            <input type="hidden" name="rol" value="Paseador">
                            <button type="submit" class="btn btn-outline-success btn-sm">Paseador</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

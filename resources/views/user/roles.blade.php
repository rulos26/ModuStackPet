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
                <th>Asignar nuevos roles</th>
            </tr>
        </thead>
        <tbody>
            @foreach($usuarios as $usuario)
                <tr>
                    <td>{{ $usuario->name }}</td>
                    <td>{{ $usuario->email }}</td>
                    <td>{{ $usuario->roles->pluck('name')->join(', ') }}</td>
                    <td>
                        <form method="POST" action="{{ route('usuarios.roles.asignar', $usuario) }}">
                            @csrf
                            <select name="roles[]" multiple class="form-control">
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" @if($usuario->roles->contains('id', $role->id)) selected @endif>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            <button class="btn btn-sm btn-primary mt-2">Asignar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

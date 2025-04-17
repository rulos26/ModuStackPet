@extends('layouts.app')

@section('template_title')
    Mi Perfil
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">
                                <i class="fas fa-user"></i> {{ __('Mi Perfil') }}
                            </span>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 text-center">
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="img-thumbnail mb-3" style="max-width: 200px;">
                                @else
                                    <div class="mb-3">
                                        <i class="fas fa-user-circle fa-5x text-secondary"></i>
                                    </div>
                                @endif
                                <h4>{{ $user->name }}</h4>
                                <p class="text-muted">{{ $user->email }}</p>
                            </div>

                            <div class="col-md-8">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <tr>
                                            <th>Estado de la Cuenta:</th>
                                            <td>
                                                @if($user->activo)
                                                    <span class="badge badge-success">Activo</span>
                                                @else
                                                    <span class="badge badge-danger">Inactivo</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Fecha de Registro:</th>
                                            <td>{{ $user->created_at->format('d/m/Y H:i:s') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Última Actualización:</th>
                                            <td>{{ $user->updated_at->format('d/m/Y H:i:s') }}</td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="mt-4">
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary">
                                        <i class="fas fa-edit"></i> {{ __('Editar Mi Perfil') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

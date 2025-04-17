@extends('layouts.app')

@section('template_title')
    Editar Usuario
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">
                                <i class="fas fa-user-edit"></i> {{ __('Editar Usuario') }}
                            </span>
                            <div class="float-right">
                                <a href="{{ route('users.index') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-arrow-left"></i> {{ __('Volver') }}
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('users.update', $user->id) }}" role="form" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">{{ __('Nombre') }}</label>
                                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group mt-3">
                                        <label for="email">{{ __('Email') }}</label>
                                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group mt-3">
                                        <label for="password">{{ __('Nueva Contraseña') }}</label>
                                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
                                        <small class="form-text text-muted">Dejar en blanco si no desea cambiar la contraseña</small>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group mt-3">
                                        <label for="password_confirmation">{{ __('Confirmar Nueva Contraseña') }}</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="role">{{ __('Rol') }}</label>
                                        <select name="role" id="role" class="form-control @error('role') is-invalid @enderror" required>
                                            <option value="">Seleccione un rol</option>
                                            @foreach($roles as $role)
                                                @if($role->name != 'superadmin')
                                                    <option value="{{ $role->name }}" {{ old('role', $user->roles->first()->name) == $role->name ? 'selected' : '' }}>
                                                        {{ $role->name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('role')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group mt-3">
                                        <label for="avatar">{{ __('Avatar') }}</label>
                                        @if($user->avatar)
                                            <div class="mb-2">
                                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar actual" class="img-thumbnail" style="max-width: 100px;">
                                            </div>
                                        @endif
                                        <input type="file" name="avatar" id="avatar" class="form-control @error('avatar') is-invalid @enderror" accept="image/*">
                                        @error('avatar')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group mt-3">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="activo" name="activo" value="1" {{ old('activo', $user->activo) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="activo">{{ __('Usuario Activo') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> {{ __('Actualizar Usuario') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

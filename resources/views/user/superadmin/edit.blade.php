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
                                <i class="fas fa-user-edit"></i> Editar Usuario
                            </span>
                            <div class="float-right">
                                <a href="{{ route('superadmin.users.show') }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-left"></i> Volver
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('superadmin.users.update', $user->id) }}" role="form" enctype="multipart/form-data">
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
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">{{ __('Email') }}</label>
                                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password">{{ __('Contraseña') }}</label>
                                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
                                        <small class="form-text text-muted">Dejar en blanco si no desea cambiar la contraseña</small>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password_confirmation">{{ __('Confirmar Contraseña') }}</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="roles">{{ __('Roles') }}</label>
                                        <select name="roles[]" id="roles" class="form-control @error('roles') is-invalid @enderror" multiple required>
                                            @foreach($roles as $role)
                                                <option value="{{ $role->id }}" {{ in_array($role->id, $user->roles->pluck('id')->toArray()) ? 'selected' : '' }}>
                                                    {{ $role->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('roles')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="active">{{ __('Estado') }}</label>
                                        <select name="active" id="active" class="form-control @error('active') is-invalid @enderror" required>
                                            <option value="1" {{ $user->active ? 'selected' : '' }}>Activo</option>
                                            <option value="0" {{ !$user->active ? 'selected' : '' }}>Inactivo</option>
                                        </select>
                                        @error('active')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="avatar">{{ __('Avatar') }}</label>
                                        <input type="file" name="avatar" id="avatar" class="form-control @error('avatar') is-invalid @enderror">
                                        <small class="form-text text-muted">Formatos permitidos: jpg, jpeg, png. Tamaño máximo: 2MB</small>
                                        @error('avatar')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    @if($user->avatar)
                                        <div class="form-group">
                                            <label>{{ __('Avatar Actual') }}</label>
                                            <div>
                                                <img src="{{ asset($user->avatar) }}" alt="Avatar actual" class="img-thumbnail" style="max-width: 100px;">
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">{{ __('Actualizar Usuario') }}</button>
                                    <a href="{{ route('superadmin.usuarios.index') }}" class="btn btn-secondary">{{ __('Cancelar') }}</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#roles').select2({
                theme: 'bootstrap-5',
                placeholder: 'Seleccione los roles',
                allowClear: true
            });
        });
    </script>
@endpush

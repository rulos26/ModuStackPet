@if(isset($user))
    <form method="POST" action="{{ route('users.update', $user->id) }}" role="form" enctype="multipart/form-data">
        @method('PUT')
@else
    <form method="POST" action="{{ route('users.store') }}" role="form" enctype="multipart/form-data">
@endif
    @csrf

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="name">{{ __('Nombre') }}</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                    value="{{ isset($user) ? old('name', $user->name) : old('name') }}" required {{ !isset($user) ? 'autofocus' : '' }}>
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group mt-3">
                <label for="email">{{ __('Email') }}</label>
                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                    value="{{ isset($user) ? old('email', $user->email) : old('email') }}" required>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group mt-3">
                <label for="password">{{ isset($user) ? __('Nueva Contraseña') : __('Contraseña') }}</label>
                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror"
                    {{ !isset($user) ? 'required' : '' }}>
                @if(isset($user))
                    <small class="form-text text-muted">Dejar en blanco si no desea cambiar la contraseña</small>
                @endif
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group mt-3">
                <label for="password_confirmation">{{ isset($user) ? __('Confirmar Nueva Contraseña') : __('Confirmar Contraseña') }}</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
                    {{ !isset($user) ? 'required' : '' }}>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="avatar">{{ __('Avatar') }}</label>
                @if(isset($user) && $user->avatar)
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
        </div>
    </div>

    <div class="form-group mt-4">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> {{ isset($user) ? __('Actualizar Perfil') : __('Crear Usuario') }}
        </button>
    </div>
</form>

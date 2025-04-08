<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ __('Formulario de Usuario') }}</h3>
    </div>
    <div class="card-body">
        <!-- Sección: Datos Personales -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">{{ __('Datos Personales') }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <!-- Nombre -->
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">{{ __('Nombre') }}</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user?->name) }}" id="name" placeholder="Nombre">
                            {!! $errors->first('name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Tipo de Documento -->
                        <div class="form-group mb-3">
                            <label for="tipo_documento" class="form-label">{{ __('Tipo de Documento') }}</label>
                            <select name="tipo_documento" class="form-select @error('tipo_documento') is-invalid @enderror" id="tipo_documento">
                                <option value="">{{ __('Seleccione un tipo de documento') }}</option>
                                @foreach($tiposDocumento as $tipo)
                                    <option value="{{ $tipo->id }}" {{ old('tipo_documento', $user?->tipo_documento) == $tipo->id ? 'selected' : '' }}>
                                        {{ $tipo->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            {!! $errors->first('tipo_documento', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Cédula -->
                        <div class="form-group mb-3">
                            <label for="cedula" class="form-label">{{ __('Cédula') }}</label>
                            <input type="number" name="cedula" class="form-control @error('cedula') is-invalid @enderror" value="{{ old('cedula', $user?->cedula) }}" id="cedula" placeholder="Cédula">
                            {!! $errors->first('cedula', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Fecha de Nacimiento -->
                        <div class="form-group mb-3">
                            <label for="fecha_nacimiento" class="form-label">{{ __('Fecha de Nacimiento') }}</label>
                            <input type="date" name="fecha_nacimiento" class="form-control @error('fecha_nacimiento') is-invalid @enderror" value="{{ old('fecha_nacimiento', $user?->fecha_nacimiento) }}" id="fecha_nacimiento">
                            {!! $errors->first('fecha_nacimiento', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección: Datos de Contacto -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">{{ __('Datos de Contacto') }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <!-- Correo Electrónico -->
                        <div class="form-group mb-3">
                            <label for="email" class="form-label">{{ __('Correo Electrónico') }}</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user?->email) }}" id="email" placeholder="Correo Electrónico">
                            {!! $errors->first('email', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Teléfono -->
                        <div class="form-group mb-3">
                            <label for="telefono" class="form-label">{{ __('Teléfono') }}</label>
                            <input type="tel" name="telefono" class="form-control @error('telefono') is-invalid @enderror" value="{{ old('telefono', $user?->telefono) }}" id="telefono" placeholder="Teléfono">
                            {!! $errors->first('telefono', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- WhatsApp -->
                        <div class="form-group mb-3">
                            <label for="whatsapp" class="form-label">{{ __('WhatsApp') }}</label>
                            <input type="tel" name="whatsapp" class="form-control @error('whatsapp') is-invalid @enderror" value="{{ old('whatsapp', $user?->whatsapp) }}" id="whatsapp" placeholder="WhatsApp">
                            {!! $errors->first('whatsapp', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección: Datos de Cuenta -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">{{ __('Datos de Cuenta') }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <!-- Contraseña -->
                        <div class="form-group mb-3">
                            <label for="password" class="form-label">{{ __('Contraseña') }}</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                </div>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="{{ __('Ingrese una contraseña') }}">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary toggle-password" data-target="#password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            {!! $errors->first('password', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Confirmar Contraseña -->
                        <div class="form-group mb-3">
                            <label for="password_confirmation" class="form-label">{{ __('Confirmar Contraseña') }}</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                </div>
                                <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" placeholder="{{ __('Confirme la contraseña') }}">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary toggle-password" data-target="#password_confirmation">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            {!! $errors->first('password_confirmation', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección: Otros -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">{{ __('Otros') }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <!-- Activo -->
                        <div class="form-group mb-3">
                            <label for="activo" class="form-label">{{ __('Activo') }}</label>
                            <div class="form-check form-switch">
                                <input type="checkbox" name="activo" class="form-check-input @error('activo') is-invalid @enderror" id="activo" value="1" 
                                    {{ old('activo', $user?->activo) == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="activo">{{ __('¿Está activo?') }}</label>
                            </div>
                            {!! $errors->first('activo', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Foto de Perfil -->
                        <div class="form-group mb-3">
                            <label for="avatar" class="form-label">{{ __('Foto de Perfil') }}</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="avatar" class="custom-file-input @error('avatar') is-invalid @enderror" id="avatar" accept="image/*">
                                    <label class="custom-file-label" for="avatar">{{ __('Seleccionar Imagen') }}</label>
                                </div>
                            </div>
                            {!! $errors->first('avatar', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                            @if($user?->avatar)
                                <div class="mt-3">
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="Foto de Perfil" class="img-thumbnail" style="max-width: 150px;">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones de acción -->
        <div class="row">
            <div class="col-md-6">
                <a href="{{ route('users.index') }}" class="btn btn-secondary w-100">{{ __('Volver al Index') }}</a>
            </div>
            <div class="col-md-6">
                <button type="submit" class="btn btn-primary w-100">{{ __('Enviar') }}</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const togglePasswordButtons = document.querySelectorAll('.toggle-password');

        togglePasswordButtons.forEach(button => {
            button.addEventListener('click', function () {
                const target = document.querySelector(this.getAttribute('data-target'));
                const icon = this.querySelector('i');

                if (target.type === 'password') {
                    target.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    target.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });
    });
</script>
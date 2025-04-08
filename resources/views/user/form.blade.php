<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="name" class="form-label">{{ __('Nombre') }}</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user?->name) }}" id="name" placeholder="Nombre">
            {!! $errors->first('name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        
        <div class="form-group mb-2 mb20">
            <label for="email" class="form-label">{{ __('Correo Electrónico') }}</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user?->email) }}" id="email" placeholder="Correo Electrónico">
            {!! $errors->first('email', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        
        <div class="form-group mb-2 mb20">
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
        
        <div class="form-group mb-2 mb20">
            <label for="cedula" class="form-label">{{ __('Cédula') }}</label>
            <input type="number" name="cedula" class="form-control @error('cedula') is-invalid @enderror" value="{{ old('cedula', $user?->cedula) }}" id="cedula" placeholder="Cédula">
            {!! $errors->first('cedula', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        
        <div class="form-group mb-2 mb20">
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
        
        <div class="form-group mb-2 mb20">
            <label for="activo" class="form-label">{{ __('Activo') }}</label>
            <div class="form-check form-switch">
                <input type="checkbox" name="activo" class="form-check-input @error('activo') is-invalid @enderror" id="activo" value="1" 
                    {{ old('activo', $user?->activo) == 1 ? 'checked' : '' }}>
                <label class="form-check-label" for="activo">{{ __('¿Está activo?') }}</label>
            </div>
            {!! $errors->first('activo', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        
        <div class="form-group mb-2 mb20">
            <label for="telefono" class="form-label">{{ __('Teléfono') }}</label>
            <input type="tel" name="telefono" class="form-control @error('telefono') is-invalid @enderror" value="{{ old('telefono', $user?->telefono) }}" id="telefono" placeholder="Teléfono">
            {!! $errors->first('telefono', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        
        <div class="form-group mb-2 mb20">
            <label for="whatsapp" class="form-label">{{ __('WhatsApp') }}</label>
            <input type="tel" name="whatsapp" class="form-control @error('whatsapp') is-invalid @enderror" value="{{ old('whatsapp', $user?->whatsapp) }}" id="whatsapp" placeholder="WhatsApp">
            {!! $errors->first('whatsapp', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        
        <div class="form-group mb-2 mb20">
            <label for="fecha_nacimiento" class="form-label">{{ __('Fecha de Nacimiento') }}</label>
            <input type="date" name="fecha_nacimiento" class="form-control @error('fecha_nacimiento') is-invalid @enderror" value="{{ old('fecha_nacimiento', $user?->fecha_nacimiento) }}" id="fecha_nacimiento" placeholder="Fecha de Nacimiento">
            {!! $errors->first('fecha_nacimiento', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="password" class="form-label">{{ __('Contraseña') }}</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">
                        <i class="fas fa-lock"></i> <!-- Ícono de candado -->
                    </span>
                </div>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="{{ __('Ingrese una contraseña') }}">
            </div>
            {!! $errors->first('password', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="password_confirmation" class="form-label">{{ __('Confirmar Contraseña') }}</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">
                        <i class="fas fa-lock"></i> <!-- Ícono de candado -->
                    </span>
                </div>
                <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" placeholder="{{ __('Confirme la contraseña') }}">
            </div>
            {!! $errors->first('password_confirmation', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Enviar') }}</button>
    </div>
</div>
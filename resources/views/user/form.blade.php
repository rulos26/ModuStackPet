<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="name" class="form-label">{{ __('Nombre') }}</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user?->name) }}" id="name" placeholder="Nombre">
            {!! $errors->first('name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="email" class="form-label">{{ __('Correo Electrónico') }}</label>
            <input type="text" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user?->email) }}" id="email" placeholder="Correo Electrónico">
            {!! $errors->first('email', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="tipo_documento" class="form-label">{{ __('Tipo de Documento') }}</label>
            <input type="text" name="tipo_documento" class="form-control @error('tipo_documento') is-invalid @enderror" value="{{ old('tipo_documento', $user?->tipo_documento) }}" id="tipo_documento" placeholder="Tipo de Documento">
            {!! $errors->first('tipo_documento', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="cedula" class="form-label">{{ __('Cédula') }}</label>
            <input type="text" name="cedula" class="form-control @error('cedula') is-invalid @enderror" value="{{ old('cedula', $user?->cedula) }}" id="cedula" placeholder="Cédula">
            {!! $errors->first('cedula', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="avatar" class="form-label">{{ __('Avatar') }}</label>
            <input type="text" name="avatar" class="form-control @error('avatar') is-invalid @enderror" value="{{ old('avatar', $user?->avatar) }}" id="avatar" placeholder="Avatar">
            {!! $errors->first('avatar', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="activo" class="form-label">{{ __('Activo') }}</label>
            <input type="text" name="activo" class="form-control @error('activo') is-invalid @enderror" value="{{ old('activo', $user?->activo) }}" id="activo" placeholder="Activo">
            {!! $errors->first('activo', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="telefono" class="form-label">{{ __('Teléfono') }}</label>
            <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror" value="{{ old('telefono', $user?->telefono) }}" id="telefono" placeholder="Teléfono">
            {!! $errors->first('telefono', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="whatsapp" class="form-label">{{ __('WhatsApp') }}</label>
            <input type="text" name="whatsapp" class="form-control @error('whatsapp') is-invalid @enderror" value="{{ old('whatsapp', $user?->whatsapp) }}" id="whatsapp" placeholder="WhatsApp">
            {!! $errors->first('whatsapp', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="fecha_nacimiento" class="form-label">{{ __('Fecha de Nacimiento') }}</label>
            <input type="text" name="fecha_nacimiento" class="form-control @error('fecha_nacimiento') is-invalid @enderror" value="{{ old('fecha_nacimiento', $user?->fecha_nacimiento) }}" id="fecha_nacimiento" placeholder="Fecha de Nacimiento">
            {!! $errors->first('fecha_nacimiento', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Enviar') }}</button>
    </div>
</div>
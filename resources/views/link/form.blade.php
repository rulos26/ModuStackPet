<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="link" class="form-label">{{ __('Link') }}</label>
            <input type="url" name="link" class="form-control @error('link') is-invalid @enderror" value="{{ old('link', $link?->link) }}" id="link" placeholder="Link">
            {!! $errors->first('link', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="nombre" class="form-label">{{ __('Nombre') }}</label>
            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre', $link?->nombre) }}" id="nombre" placeholder="Nombre">
            {!! $errors->first('nombre', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="id_modulo" class="form-label">{{ __('Modulo') }}</label>
            <select name="id_modulo" class="form-control @error('id_modulo') is-invalid @enderror" id="id_modulo">
                <option value="" disabled selected>Seleccione un módulo</option>
                @foreach($modulo as $modulo)
                    <option value="{{ $modulo->id }}" {{ old('id_modulo', optional($link)->id_modulo) == $modulo->id ? 'selected' : '' }}>
                        {{ $modulo->nombre }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('id_modulo', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Siguiente') }}</button>
    </div>
</div>
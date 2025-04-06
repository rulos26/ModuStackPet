<div class="row padding-1 p-1">
    <div class="col-md-12">
        <!-- Campo para el título -->
        <div class="form-group mb-2 mb20">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" name="titulo" class="form-control @error('titulo') is-invalid @enderror" 
                   value="{{ old('titulo', $mensajeDeBienvenida?->titulo) }}" id="titulo" placeholder="Título">
            <!-- Mostrar mensaje de error si el campo no es válido -->
            {!! $errors->first('titulo', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <!-- Campo para la descripción -->
        <div class="form-group mb-2 mb20">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" 
                      id="descripcion" placeholder="Descripción" rows="4">{{ old('descripcion', $mensajeDeBienvenida?->descripcion) }}</textarea>
            <!-- Mostrar mensaje de error si el campo no es válido -->
            {!! $errors->first('descripcion', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>

    <!-- Botón para enviar el formulario -->
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">Enviar</button>
    </div>
</div>
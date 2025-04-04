<div class="row padding-1 p-1">
    <div class="col-md-12">
        <div class="form-group mb-2 mb20">
            <input type="hidden" name="cedula" class="form-control @error('cedula') is-invalid @enderror" value="{{ $cedula }}" id="cedula" placeholder="Cedula">
        </div>
        <div class="form-group mb-2 mb20">
            <label for="nombre" class="form-label">{{ __('Nombre') }}</label>
            <input type="hidden" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="hola" id="nombre" placeholder="Nombre">
        </div>
        <div>
            <canvas id="signature-pad" class="signature-pad" style="border: 1px solid #000; width: 100%; max-width: 400px; height: 200px;"></canvas>
            <br>
            <button type="button" id="clear" class="btn btn-danger">Limpiar</button>
            <button type="button" id="save" class="btn btn-success">Guardar</button>
        </div>
        <div id="signature-image" style="margin-top: 20px;">
            <!-- Aquí se mostrará la imagen guardada -->
        </div>
    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Siguiente') }}</button>
    </div>
</div>

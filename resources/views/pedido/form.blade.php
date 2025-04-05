<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="cliente" class="form-label">{{ __('Cliente') }}</label>
            <input type="text" name="cliente" class="form-control @error('cliente') is-invalid @enderror" value="{{ old('cliente', $pedido?->cliente) }}" id="cliente" placeholder="Cliente">
            {!! $errors->first('cliente', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="producto" class="form-label">{{ __('Producto') }}</label>
            <input type="text" name="producto" class="form-control @error('producto') is-invalid @enderror" value="{{ old('producto', $pedido?->producto) }}" id="producto" placeholder="Producto">
            {!! $errors->first('producto', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="ciclo" class="form-label">{{ __('Ciclo') }}</label>
            <input type="text" name="ciclo" class="form-control @error('ciclo') is-invalid @enderror" value="{{ old('ciclo', $pedido?->ciclo) }}" id="ciclo" placeholder="Ciclo">
            {!! $errors->first('ciclo', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="estado_pedido" class="form-label">{{ __('Estado Pedido') }}</label>
            <input type="text" name="estado_pedido" class="form-control @error('estado_pedido') is-invalid @enderror" value="{{ old('estado_pedido', $pedido?->estado_pedido) }}" id="estado_pedido" placeholder="Estado Pedido">
            {!! $errors->first('estado_pedido', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="estado_deuda" class="form-label">{{ __('Estado Deuda') }}</label>
            <input type="text" name="estado_deuda" class="form-control @error('estado_deuda') is-invalid @enderror" value="{{ old('estado_deuda', $pedido?->estado_deuda) }}" id="estado_deuda" placeholder="Estado Deuda">
            {!! $errors->first('estado_deuda', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="descripcion" class="form-label">{{ __('Descripcion') }}</label>
            <input type="text" name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" value="{{ old('descripcion', $pedido?->descripcion) }}" id="descripcion" placeholder="Descripcion">
            {!! $errors->first('descripcion', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="valor_catalogo" class="form-label">{{ __('Valor Catalogo') }}</label>
            <input type="text" name="valor_catalogo" class="form-control @error('valor_catalogo') is-invalid @enderror" value="{{ old('valor_catalogo', $pedido?->valor_catalogo) }}" id="valor_catalogo" placeholder="Valor Catalogo">
            {!! $errors->first('valor_catalogo', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="valor_lista" class="form-label">{{ __('Valor Lista') }}</label>
            <input type="text" name="valor_lista" class="form-control @error('valor_lista') is-invalid @enderror" value="{{ old('valor_lista', $pedido?->valor_lista) }}" id="valor_lista" placeholder="Valor Lista">
            {!! $errors->first('valor_lista', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>

<form>
    <h4 class="mb-4 text-center">Formulario de Pedido</h4>
    
    <div class="row">
        <!-- Cliente y Producto -->
        <div class="col-md-6">
            <div class="mb-3">
                <label for="cliente" class="form-label">Cliente</label>
                <input type="text" name="cliente" class="form-control" id="cliente" placeholder="Nombre del Cliente">
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="producto" class="form-label">Producto</label>
                <input type="text" name="producto" class="form-control" id="producto" placeholder="Nombre del Producto">
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Estado Pedido -->
        <div class="col-md-6">
            <div class="mb-3">
                <label for="estado_pedido" class="form-label">Estado Pedido</label>
                <select name="estado_pedido" class="form-select" id="estado_pedido">
                    <option selected>Seleccione un estado</option>
                    <option value="pendiente">Pendiente</option>
                    <option value="procesado">Procesado</option>
                    <option value="entregado">Entregado</option>
                </select>
            </div>
        </div>

        <!-- Ciclo -->
        <div class="col-md-6">
            <div class="mb-3">
                <label for="ciclo" class="form-label">Ciclo</label>
                <input type="text" name="ciclo" class="form-control" id="ciclo" placeholder="Ciclo">
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Estado Deuda -->
        <div class="col-md-4">
            <div class="mb-3">
                <label for="estado_deuda" class="form-label">Estado Deuda</label>
                <input type="text" name="estado_deuda" class="form-control" id="estado_deuda" placeholder="Estado Deuda">
            </div>
        </div>

        <!-- Valor Catálogo -->
        <div class="col-md-4">
            <div class="mb-3">
                <label for="valor_catalogo" class="form-label">Valor Catálogo</label>
                <input type="text" name="valor_catalogo" class="form-control" id="valor_catalogo" placeholder="Valor del Catálogo">
            </div>
        </div>

        <!-- Valor Lista -->
        <div class="col-md-4">
            <div class="mb-3">
                <label for="valor_lista" class="form-label">Valor Lista</label>
                <input type="text" name="valor_lista" class="form-control" id="valor_lista" placeholder="Valor de Lista">
            </div>
        </div>
    </div>

    <!-- Descripción -->
    <div class="mb-3">
        <label for="descripcion" class="form-label">Descripción</label>
        <textarea name="descripcion" class="form-control" id="descripcion" rows="3" placeholder="Ingrese una breve descripción"></textarea>
    </div>

    <!-- Botón de Enviar -->
    <div class="d-grid">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="bi bi-send"></i> Enviar Pedido
        </button>
    </div>
</form>
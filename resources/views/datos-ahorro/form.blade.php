<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="user_id" class="form-label">{{ __('User Id') }}</label>
            <input type="text" name="user_id" class="form-control @error('user_id') is-invalid @enderror" value="{{ old('user_id', $datosAhorro?->user_id) }}" id="user_id" placeholder="User Id">
            {!! $errors->first('user_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="sueldo" class="form-label">{{ __('Sueldo') }}</label>
            <input type="text" name="sueldo" class="form-control @error('sueldo') is-invalid @enderror" value="{{ old('sueldo', $datosAhorro?->sueldo) }}" id="sueldo" placeholder="Sueldo">
            {!! $errors->first('sueldo', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="metodo_ahorro_id" class="form-label">{{ __('Metodo Ahorro Id') }}</label>
            <input type="text" name="metodo_ahorro_id" class="form-control @error('metodo_ahorro_id') is-invalid @enderror" value="{{ old('metodo_ahorro_id', $datosAhorro?->metodo_ahorro_id) }}" id="metodo_ahorro_id" placeholder="Metodo Ahorro Id">
            {!! $errors->first('metodo_ahorro_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="fecha_inicio" class="form-label">{{ __('Fecha Inicio') }}</label>
            <input type="text" name="fecha_inicio" class="form-control @error('fecha_inicio') is-invalid @enderror" value="{{ old('fecha_inicio', $datosAhorro?->fecha_inicio) }}" id="fecha_inicio" placeholder="Fecha Inicio">
            {!! $errors->first('fecha_inicio', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="fecha_fin" class="form-label">{{ __('Fecha Fin') }}</label>
            <input type="text" name="fecha_fin" class="form-control @error('fecha_fin') is-invalid @enderror" value="{{ old('fecha_fin', $datosAhorro?->fecha_fin) }}" id="fecha_fin" placeholder="Fecha Fin">
            {!! $errors->first('fecha_fin', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="mes_id" class="form-label">{{ __('Mes Id') }}</label>
            <input type="text" name="mes_id" class="form-control @error('mes_id') is-invalid @enderror" value="{{ old('mes_id', $datosAhorro?->mes_id) }}" id="mes_id" placeholder="Mes Id">
            {!! $errors->first('mes_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>
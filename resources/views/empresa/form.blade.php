<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="nombre_legal" class="form-label">{{ __('Nombre Legal') }}</label>
            <input type="text" name="nombre_legal" class="form-control @error('nombre_legal') is-invalid @enderror" value="{{ old('nombre_legal', $empresa?->nombre_legal) }}" id="nombre_legal" placeholder="Nombre Legal">
            {!! $errors->first('nombre_legal', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="nombre_comercial" class="form-label">{{ __('Nombre Comercial') }}</label>
            <input type="text" name="nombre_comercial" class="form-control @error('nombre_comercial') is-invalid @enderror" value="{{ old('nombre_comercial', $empresa?->nombre_comercial) }}" id="nombre_comercial" placeholder="Nombre Comercial">
            {!! $errors->first('nombre_comercial', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="nit" class="form-label">{{ __('Nit') }}</label>
            <input type="text" name="nit" class="form-control @error('nit') is-invalid @enderror" value="{{ old('nit', $empresa?->nit) }}" id="nit" placeholder="Nit">
            {!! $errors->first('nit', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="dv" class="form-label">{{ __('Dv') }}</label>
            <input type="text" name="dv" class="form-control @error('dv') is-invalid @enderror" value="{{ old('dv', $empresa?->dv) }}" id="dv" placeholder="Dv">
            {!! $errors->first('dv', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="representante_legal" class="form-label">{{ __('Representante Legal') }}</label>
            <input type="text" name="representante_legal" class="form-control @error('representante_legal') is-invalid @enderror" value="{{ old('representante_legal', $empresa?->representante_legal) }}" id="representante_legal" placeholder="Representante Legal">
            {!! $errors->first('representante_legal', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="tipo_empresa_id" class="form-label">{{ __('Tipo Empresa Id') }}</label>
            <input type="text" name="tipo_empresa_id" class="form-control @error('tipo_empresa_id') is-invalid @enderror" value="{{ old('tipo_empresa_id', $empresa?->tipo_empresa_id) }}" id="tipo_empresa_id" placeholder="Tipo Empresa Id">
            {!! $errors->first('tipo_empresa_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="telefono" class="form-label">{{ __('Telefono') }}</label>
            <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror" value="{{ old('telefono', $empresa?->telefono) }}" id="telefono" placeholder="Telefono">
            {!! $errors->first('telefono', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input type="text" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $empresa?->email) }}" id="email" placeholder="Email">
            {!! $errors->first('email', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="direccion" class="form-label">{{ __('Direccion') }}</label>
            <input type="text" name="direccion" class="form-control @error('direccion') is-invalid @enderror" value="{{ old('direccion', $empresa?->direccion) }}" id="direccion" placeholder="Direccion">
            {!! $errors->first('direccion', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="ciudad_id" class="form-label">{{ __('Ciudad Id') }}</label>
            <input type="text" name="ciudad_id" class="form-control @error('ciudad_id') is-invalid @enderror" value="{{ old('ciudad_id', $empresa?->ciudad_id) }}" id="ciudad_id" placeholder="Ciudad Id">
            {!! $errors->first('ciudad_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="departamento_id" class="form-label">{{ __('Departamento Id') }}</label>
            <input type="text" name="departamento_id" class="form-control @error('departamento_id') is-invalid @enderror" value="{{ old('departamento_id', $empresa?->departamento_id) }}" id="departamento_id" placeholder="Departamento Id">
            {!! $errors->first('departamento_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="sector_id" class="form-label">{{ __('Sector Id') }}</label>
            <input type="text" name="sector_id" class="form-control @error('sector_id') is-invalid @enderror" value="{{ old('sector_id', $empresa?->sector_id) }}" id="sector_id" placeholder="Sector Id">
            {!! $errors->first('sector_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="logo" class="form-label">{{ __('Logo') }}</label>
            <input type="text" name="logo" class="form-control @error('logo') is-invalid @enderror" value="{{ old('logo', $empresa?->logo) }}" id="logo" placeholder="Logo">
            {!! $errors->first('logo', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="estado" class="form-label">{{ __('Estado') }}</label>
            <input type="text" name="estado" class="form-control @error('estado') is-invalid @enderror" value="{{ old('estado', $empresa?->estado) }}" id="estado" placeholder="Estado">
            {!! $errors->first('estado', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>
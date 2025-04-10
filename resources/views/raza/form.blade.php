@if ($errors->any())
<div class="alert alert-danger">
    <strong>{{ __('¡Ups!') }}</strong> {{ __('Hay algunos problemas con los datos ingresados.') }}<br><br>
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>{{ __('Tipo Mascota:') }}</strong>
            <input type="text" name="tipo_mascota" class="form-control @error('tipo_mascota') is-invalid @enderror"
                placeholder="{{ __('Tipo de mascota') }}"
                value="{{ old('tipo_mascota', $raza->tipo_mascota ?? '') }}"
                required>
            @error('tipo_mascota')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>{{ __('Nombre:') }}</strong>
            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                placeholder="{{ __('Nombre de la raza') }}"
                value="{{ old('nombre', $raza->nombre ?? '') }}"
                required>
            @error('nombre')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
        <button type="submit" class="btn btn-primary">
            <i class="fa fa-fw fa-save"></i> {{ __('Guardar') }}
        </button>
        <a class="btn btn-secondary" href="{{ route('razas.index') }}">
            <i class="fa fa-fw fa-times"></i> {{ __('Cancelar') }}
        </a>
    </div>
</div>

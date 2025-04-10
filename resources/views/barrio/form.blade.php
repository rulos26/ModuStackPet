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
            <strong>{{ __('Nombre:') }}</strong>
            <input type="text" name="nombre" class="form-control" placeholder="{{ __('Nombre del barrio') }}" value="{{ old('nombre', $barrio->nombre ?? '') }}">
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>{{ __('Localidad:') }}</strong>
            <input type="text" name="localidad" class="form-control" placeholder="{{ __('Nombre de la localidad') }}" value="{{ old('localidad', $barrio->localidad ?? '') }}">
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
        <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
        <a class="btn btn-secondary" href="{{ route('barrios.index') }}">{{ __('Cancelar') }}</a>
    </div>
</div>

<div class="box box-info padding-1">
    <div class="box-body">

        <div class="form-group">
{{--             {{ Form::text('nombre',  $tipoDocumentos->nombre , ['class' => 'form-control' . ($errors->has('nombre') ? ' is-invalid' : ''), 'placeholder' => 'Nombre']) }}
 --}}            <input type="text" name="nombre" value="{{ $tipoDocumentos->nombre }}" class="form-control{{ $errors->has('nombre') ? ' is-invalid' : '' }}" placeholder="Nombre">

            {!! $errors->first('nombre', '<div class="invalid-feedback">:message</div>') !!}
        </div>


    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
        <a class="btn btn-secondary" href="{{ route('generos.index') }}"> {{ __('Cancelar') }}</a>
    </div>
</div>

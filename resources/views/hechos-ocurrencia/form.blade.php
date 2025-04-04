<div class="row padding-1 p-1">
    <input type="hidden" name="cedula_numero" value="{{ $cedula ?? old('cedula_numero') }}">
   
    <div class="col-md-4">
        
        <div class="form-group mb-2 mb20">
            <label for="dia" class="form-label">{{ __('Día') }}</label>
            <input type="date" name="dia" class="form-control @error('dia') is-invalid @enderror"
                value="{{ old('dia', $hechosOcurrencia?->dia) }}" id="dia">
            {!! $errors->first('dia', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

 
        <div class="form-group mb-2 mb20">
            <label for="motivo_muerte" class="form-label">{{ __('Motivo Muerte') }}</label>
            <select name="motivo_muerte" id="motivo_muerte"
                class="form-control @error('motivo_muerte') is-invalid @enderror">
                <option value="">Seleccione un motivo</option>
                @foreach($motivo as $item)
                <option value="{{ $item->id }}" {{ old('motivo_muerte', $hechosOcurrencia?->motivo_muerte) == $item->id
                    ? 'selected' : '' }}>
                    {{ $item->nombre }}
                </option>
                @endforeach
            </select>
            {!! $errors->first('motivo_muerte', '<div class="invalid-feedback" role="alert"><strong>:message</strong>
            </div>') !!}
        </div>

        
       


    </div>
    <div class="col-md-4">
        
    

        <div class="form-group mb-2 mb20">
            <label for="horas" class="form-label">{{ __('Horas') }}</label>
            <input type="time" name="horas" class="form-control @error('horas') is-invalid @enderror"
                value="{{ old('horas', $hechosOcurrencia?->horas) }}" id="horas" placeholder="Horas">
            {!! $errors->first('horas', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>')
            !!}
        </div>
      
      
        <div class="form-group mb-2 mb20">
            <label for="deceso_se_origna" class="form-label">{{ __('Deceso Se Origna') }}</label>
            <input type="text" name="deceso_se_origna"
                class="form-control @error('deceso_se_origna') is-invalid @enderror"
                value="{{ old('deceso_se_origna', $hechosOcurrencia?->deceso_se_origna) }}" id="deceso_se_origna"
                placeholder="Deceso Se Origna">
            {!! $errors->first('deceso_se_origna', '<div class="invalid-feedback" role="alert"><strong>:message</strong>
            </div>') !!}
        </div>


    </div>
    <div class="col-md-4">
    

        <div class="form-group mb-2 mb20">
            <label for="lugar" class="form-label">{{ __('Lugar') }}</label>
            <input type="text" name="lugar" class="form-control @error('lugar') is-invalid @enderror"
                value="{{ old('lugar', $hechosOcurrencia?->lugar) }}" id="lugar" placeholder="Lugar">
            {!! $errors->first('lugar', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>')
            !!}
        </div>
       

        <div class="form-group mb-2 mb20">
            <label for="otros" class="form-label">{{ __('Otros') }}</label>
            <input type="text" name="otros" class="form-control @error('otros') is-invalid @enderror"
                value="{{ old('otros', $hechosOcurrencia?->otros) }}" id="otros" placeholder="Otros">
            {!! $errors->first('otros', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>')
            !!}
        </div>
      


    </div>
    <div class="col-md-12">
        <div class="form-group mb-2 mb20">
            <label for="donde_fallese" class="form-label">{{ __('¿Donde Fallese el Afiliado?') }}</label>
            <textarea name="donde_fallese" class="form-control @error('donde_fallese') is-invalid @enderror" id="donde_fallese" placeholder="Donde Fallese">{{ old('donde_fallese', $hechosOcurrencia?->donde_fallese) }}</textarea>
            {!! $errors->first('donde_fallese', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>
    
    <div class="col-md-12">
        <div class="form-group mb-2 mb20">
            <label for="funeraria" class="form-label">{{ __('¿La Funeraria?') }}</label>
            <textarea name="funeraria" class="form-control @error('funeraria') is-invalid @enderror" id="funeraria" placeholder="Funeraria">{{ old('funeraria', $hechosOcurrencia?->funeraria) }}</textarea>
            {!! $errors->first('funeraria', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>
    
    <div class="col-md-12">
        <div class="form-group mb-2 mb20">
            <label for="fallecido" class="form-label">{{ __('¿El Fallecido?') }}</label>
            <textarea name="fallecido" class="form-control @error('fallecido') is-invalid @enderror" id="fallecido" placeholder="Fallecido">{{ old('fallecido', $hechosOcurrencia?->fallecido) }}</textarea>
            {!! $errors->first('fallecido', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>
    
    <div class="col-md-12">
        <div class="form-group mb-2 mb20">
            <label for="cuerpo_fue" class="form-label">{{ __('¿El Cuerpo Fue?') }}</label>
            <textarea name="cuerpo_fue" class="form-control @error('cuerpo_fue') is-invalid @enderror" id="cuerpo_fue" placeholder="Cuerpo Fue">{{ old('cuerpo_fue', $hechosOcurrencia?->cuerpo_fue) }}</textarea>
            {!! $errors->first('cuerpo_fue', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>
    
    <div class="col-md-12">
        <div class="form-group mb-2 mb20">
            <label for="servicos_funerarios" class="form-label">{{ __('¿Quien sufrago Servicos Funerarios?') }}</label>
            <textarea name="servicos_funerarios" class="form-control @error('servicos_funerarios') is-invalid @enderror" id="servicos_funerarios" placeholder="Servicos Funerarios">{{ old('servicos_funerarios', $hechosOcurrencia?->servicos_funerarios) }}</textarea>
            {!! $errors->first('servicos_funerarios', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>
    






    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Siguiente') }}</button>
    </div>
</div>
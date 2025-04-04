<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="name" class="form-label">{{ __('Name') }}</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $paise?->name) }}" id="name" placeholder="Name">
            {!! $errors->first('name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="iso_name" class="form-label">{{ __('Iso Name') }}</label>
            <input type="text" name="iso_name" class="form-control @error('iso_name') is-invalid @enderror" value="{{ old('iso_name', $paise?->iso_name) }}" id="iso_name" placeholder="Iso Name">
            {!! $errors->first('iso_name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="alfa2" class="form-label">{{ __('Alfa2') }}</label>
            <input type="text" name="alfa2" class="form-control @error('alfa2') is-invalid @enderror" value="{{ old('alfa2', $paise?->alfa2) }}" id="alfa2" placeholder="Alfa2">
            {!! $errors->first('alfa2', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="alfa3" class="form-label">{{ __('Alfa3') }}</label>
            <input type="text" name="alfa3" class="form-control @error('alfa3') is-invalid @enderror" value="{{ old('alfa3', $paise?->alfa3) }}" id="alfa3" placeholder="Alfa3">
            {!! $errors->first('alfa3', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="numerico" class="form-label">{{ __('Numerico') }}</label>
            <input type="text" name="numerico" class="form-control @error('numerico') is-invalid @enderror" value="{{ old('numerico', $paise?->numerico) }}" id="numerico" placeholder="Numerico">
            {!! $errors->first('numerico', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Siguiente') }}</button>
    </div>
</div>
<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            {{-- <label for="cedula" class="form-label">{{ __('Cedula') }}</label>
             --}}<input type="hidden" name="cedula" class="form-control @error('cedula') is-invalid @enderror" value="{{ $cedula }}" id="cedula" placeholder="Cedula">
            {!! $errors->first('cedula', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        {{-- <div class="form-group mb-2 mb20">
            <label for="path" class="form-label">{{ __('Path') }}</label>
            <input type="text" name="path" class="form-control @error('path') is-invalid @enderror" value="{{ old('path', $photo?->path) }}" id="path" placeholder="Path">
            {!! $errors->first('path', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div> --}}
        <div class="form-group">
            <label for="photo">Foto:</label>
            <input type="file" class="form-control" name="photo" id="photo">
            @error('photo')
                <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
            @enderror
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Siguiente') }}</button>
    </div>
</div>
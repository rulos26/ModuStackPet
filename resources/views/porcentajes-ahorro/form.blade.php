<div class="row padding-1 p-1">
    <div class="col-md-12">


        <div class="form-group mb-2 mb-20">
            <label for="metodo_id" class="form-label">{{ __('Método') }}</label>
            <select name="metodo_id" class="form-control @error('metodo_id') is-invalid @enderror" id="metodo_id">
                <option value="">Seleccione un método</option>
                @foreach($metodos as $metodo)
                <option value="{{ $metodo->id }}" {{ old('metodo_id', $porcentajesAhorro?->metodo_id) == $metodo->id ?
                    'selected' : '' }}>
                    {{ $metodo->nom_metodo }}
                    <!-- Corrección aquí -->
                </option>
                @endforeach
            </select>
            @error('metodo_id')
            <div class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="porcentaje_1" class="form-label">{{ __('Porcentaje 1') }}</label>
            <input type="number" name="porcentaje_1" step="0.01" class="form-control @error('porcentaje_1') is-invalid @enderror"
                value="{{ old('porcentaje_1', $porcentajesAhorro?->porcentaje_1) }}" id="porcentaje_1"
                placeholder="Ingrese porcentaje 1">
            @error('porcentaje_1')
                <div class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></div>
            @enderror
        </div>
        
        <div class="form-group mb-3">
            <label for="porcentaje_2" class="form-label">{{ __('Porcentaje 2') }}</label>
            <input type="number" name="porcentaje_2" step="0.01" class="form-control @error('porcentaje_2') is-invalid @enderror"
                value="{{ old('porcentaje_2', $porcentajesAhorro?->porcentaje_2) }}" id="porcentaje_2"
                placeholder="Ingrese porcentaje 2">
            @error('porcentaje_2')
                <div class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></div>
            @enderror
        </div>
        
        <div class="form-group mb-3">
            <label for="porcentaje_3" class="form-label">{{ __('Porcentaje 3') }}</label>
            <input type="number" name="porcentaje_3" step="0.01" class="form-control @error('porcentaje_3') is-invalid @enderror"
                value="{{ old('porcentaje_3', $porcentajesAhorro?->porcentaje_3) }}" id="porcentaje_3"
                placeholder="Ingrese porcentaje 3">
            @error('porcentaje_3')
                <div class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></div>
            @enderror
        </div>
        
        <div class="form-group mb-3">
            <label for="porcentaje_4" class="form-label">{{ __('Porcentaje 4') }}</label>
            <input type="number" name="porcentaje_4" step="0.01" class="form-control @error('porcentaje_4') is-invalid @enderror"
                value="{{ old('porcentaje_4', $porcentajesAhorro?->porcentaje_4) }}" id="porcentaje_4"
                placeholder="Ingrese porcentaje 4">
            @error('porcentaje_4')
                <div class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></div>
            @enderror
        </div>
        
    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>
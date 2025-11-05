{{-- Campos comunes para crear y editar registros de vacunas y certificaciones --}}

{{-- Selección de Mascota --}}
<div class="form-group">
    <label for="id_mascota">Mascota</label>
    <select name="id_mascota" id="id_mascota" class="form-control @error('id_mascota') is-invalid @enderror" required>
        <option value="">Seleccione una mascota</option>
        @foreach($mascotas as $mascota)
            <option value="{{ $mascota->id }}" {{ (old('id_mascota', $vacunasCertificacione->id_mascota ?? '') == $mascota->id) ? 'selected' : '' }}>
                {{ $mascota->nombre }}@if($mascota->raza) ({{ $mascota->raza->tipo_mascota ?? 'Mascota' }})@endif
            </option>
        @endforeach
    </select>
    @error('id_mascota')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{{-- Fecha de Última Vacuna --}}
<div class="form-group">
    <label for="fecha_ultima_vacuna">Fecha de Última Vacuna</label>
    <input type="date" name="fecha_ultima_vacuna" id="fecha_ultima_vacuna"
           class="form-control @error('fecha_ultima_vacuna') is-invalid @enderror"
           value="{{ old('fecha_ultima_vacuna', $vacunasCertificacione->fecha_ultima_vacuna ?? '') }}"
           max="{{ date('Y-m-d') }}" required>
    @error('fecha_ultima_vacuna')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{{-- Operaciones --}}
<div class="form-group">
    <label for="operaciones">Operaciones Realizadas</label>
    <textarea name="operaciones" id="operaciones"
              class="form-control @error('operaciones') is-invalid @enderror"
              rows="3">{{ old('operaciones', $vacunasCertificacione->operaciones ?? '') }}</textarea>
    @error('operaciones')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{{-- Certificado Veterinario --}}
<div class="form-group">
    <label for="certificado_veterinario">Certificado Veterinario</label>
    <input type="file" name="certificado_veterinario" id="certificado_veterinario"
           class="form-control-file @error('certificado_veterinario') is-invalid @enderror"
           accept=".pdf,.jpg,.jpeg,.png">
    <small class="form-text text-muted">
        Formatos permitidos: PDF, JPG, JPEG, PNG. Tamaño máximo: 2MB
    </small>
    @error('certificado_veterinario')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    @if(isset($vacunasCertificacione) && $vacunasCertificacione->certificado_veterinario)
        <small class="form-text text-muted">
            Certificado actual:
            <a href="{{ asset('storage/' . $vacunasCertificacione->certificado_veterinario) }}" target="_blank">
                Ver certificado
            </a>
        </small>
    @endif
</div>

{{-- Cédula del Propietario --}}
<div class="form-group">
    <label for="cedula_propietario">Cédula del Propietario</label>
    <input type="file" name="cedula_propietario" id="cedula_propietario"
           class="form-control-file @error('cedula_propietario') is-invalid @enderror"
           accept=".pdf,.jpg,.jpeg,.png">
    <small class="form-text text-muted">
        Formatos permitidos: PDF, JPG, JPEG, PNG. Tamaño máximo: 2MB
    </small>
    @error('cedula_propietario')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    @if(isset($vacunasCertificacione) && $vacunasCertificacione->cedula_propietario)
        <small class="form-text text-muted">
            Cédula actual:
            <a href="{{ asset('storage/' . $vacunasCertificacione->cedula_propietario) }}" target="_blank">
                Ver cédula
            </a>
        </small>
    @endif
</div>

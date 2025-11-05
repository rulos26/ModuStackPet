<!-- Sección: Datos Personales -->
 <div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">{{ __('Datos Personales') }}</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <!-- Nombre -->
                <div class="form-group mb-3">
                    <label for="name" class="form-label">{{ __('Nombre') }}</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user?->name) }}" id="name" placeholder="Nombre">
                    {!! $errors->first('name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>

            <div class="col-md-6">
                <!-- Tipo de Documento -->
                <div class="form-group mb-3">
                    <label for="tipo_documento" class="form-label">{{ __('Tipo de Documento') }}</label>
                    <select name="tipo_documento" class="form-select @error('tipo_documento') is-invalid @enderror" id="tipo_documento">
                        <option value="">{{ __('Seleccione un tipo de documento') }}</option>
                        @foreach($tiposDocumento as $tipo)
                            <option value="{{ $tipo->id }}" {{ old('tipo_documento', $user?->tipo_documento) == $tipo->id ? 'selected' : '' }}>
                                {{ $tipo->nombre }}
                            </option>
                        @endforeach
                    </select>
                    {!! $errors->first('tipo_documento', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>

            <div class="col-md-6">
                <!-- Cédula -->
                <div class="form-group mb-3">
                    <label for="cedula" class="form-label">{{ __('Cédula') }}</label>
                    <input type="number" name="cedula" class="form-control @error('cedula') is-invalid @enderror" 
                           value="{{ old('cedula', $user?->cedula) }}" id="cedula" placeholder="Cédula" 
                           minlength="6" maxlength="12" required>
                    {!! $errors->first('cedula', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>

            <div class="col-md-6">
                <!-- Fecha de Nacimiento -->
                <div class="form-group mb-3">
                    <label for="fecha_nacimiento" class="form-label">{{ __('Fecha de Nacimiento') }}</label>
                    @php
                        // Formatear la fecha correctamente para el input type="date" (formato Y-m-d)
                        $fechaNacimiento = null;
                        if (old('fecha_nacimiento')) {
                            $fechaNacimiento = old('fecha_nacimiento');
                        } elseif ($user && $user->fecha_nacimiento) {
                            // Si es un objeto Carbon/Date, formatear a Y-m-d
                            if ($user->fecha_nacimiento instanceof \Carbon\Carbon || $user->fecha_nacimiento instanceof \DateTime) {
                                $fechaNacimiento = $user->fecha_nacimiento->format('Y-m-d');
                            } elseif (is_string($user->fecha_nacimiento)) {
                                // Si es string, intentar parsearlo y formatearlo
                                try {
                                    $fechaNacimiento = \Carbon\Carbon::parse($user->fecha_nacimiento)->format('Y-m-d');
                                } catch (\Exception $e) {
                                    $fechaNacimiento = $user->fecha_nacimiento;
                                }
                            } else {
                                $fechaNacimiento = $user->fecha_nacimiento;
                            }
                        }
                        // También verificar en el cliente si no está en el usuario
                        if (!$fechaNacimiento && $user && $user->cliente && $user->cliente->fecha_nacimiento) {
                            if ($user->cliente->fecha_nacimiento instanceof \Carbon\Carbon || $user->cliente->fecha_nacimiento instanceof \DateTime) {
                                $fechaNacimiento = $user->cliente->fecha_nacimiento->format('Y-m-d');
                            } elseif (is_string($user->cliente->fecha_nacimiento)) {
                                try {
                                    $fechaNacimiento = \Carbon\Carbon::parse($user->cliente->fecha_nacimiento)->format('Y-m-d');
                                } catch (\Exception $e) {
                                    $fechaNacimiento = $user->cliente->fecha_nacimiento;
                                }
                            } else {
                                $fechaNacimiento = $user->cliente->fecha_nacimiento;
                            }
                        }
                    @endphp
                    <input type="date" name="fecha_nacimiento" class="form-control @error('fecha_nacimiento') is-invalid @enderror" 
                           value="{{ $fechaNacimiento }}" id="fecha_nacimiento" 
                           max="{{ now()->subYears(18)->format('Y-m-d') }}" required>
                    {!! $errors->first('fecha_nacimiento', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sección: Datos de Contacto -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">{{ __('Datos de Contacto') }}</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <!-- Correo Electrónico -->
                <div class="form-group mb-3">
                    <label for="email" class="form-label">{{ __('Correo Electrónico') }}</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user?->email) }}" id="email" placeholder="Correo Electrónico">
                    {!! $errors->first('email', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>

            <div class="col-md-6">
                <!-- Teléfono -->
                <div class="form-group mb-3">
                    <label for="telefono" class="form-label">{{ __('Teléfono') }}</label>
                    <input type="tel" name="telefono" class="form-control @error('telefono') is-invalid @enderror" value="{{ old('telefono', $user?->telefono) }}" id="telefono" placeholder="Teléfono">
                    {!! $errors->first('telefono', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>

            <div class="col-md-6">
                <!-- WhatsApp -->
                <div class="form-group mb-3">
                    <label for="whatsapp" class="form-label">{{ __('WhatsApp') }}</label>
                    <input type="tel" name="whatsapp" class="form-control @error('whatsapp') is-invalid @enderror" value="{{ old('whatsapp', $user?->whatsapp ?? $user?->cliente?->whatsapp) }}" id="whatsapp" placeholder="WhatsApp">
                    {!! $errors->first('whatsapp', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sección: Datos de Ubicación -->
@php
    $ciudades = \App\Models\Ciudade::orderBy('municipio')->get();
    
    // Buscar Bogotá como ciudad por defecto
    $bogota = \App\Models\Ciudade::where('municipio', 'LIKE', '%Bogotá%')
        ->orWhere('municipio', 'LIKE', '%Bogota%')
        ->first();
    
    $ciudadDefault = $bogota ? $bogota->id_municipio : null;
    
    // Si el usuario tiene una ciudad seleccionada, usar esa; si no, usar Bogotá por defecto
    $ciudadSeleccionada = old('ciudad_id', $user?->cliente?->ciudad_id ?? $ciudadDefault);
    
    // Cargar solo barrios de Engativá
    $barrios = \App\Models\Barrio::where('localidad', 'Engativá')
        ->orWhere('localidad', 'like', '%Engativá%')
        ->orderBy('nombre')
        ->get();
    
    // Si el usuario tiene un barrio seleccionado, asegurarse de que esté en la lista
    if (isset($user) && $user->cliente && $user->cliente->barrio_id) {
        $barrioSeleccionado = \App\Models\Barrio::find($user->cliente->barrio_id);
        if ($barrioSeleccionado && !$barrios->contains('id', $barrioSeleccionado->id)) {
            $barrios->push($barrioSeleccionado);
        }
    }
@endphp

<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">{{ __('Datos de Ubicación') }}</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <!-- Dirección -->
                <div class="form-group mb-3">
                    <label for="direccion" class="form-label">{{ __('Dirección') }}</label>
                    <input type="text" name="direccion" class="form-control @error('direccion') is-invalid @enderror" 
                           value="{{ old('direccion', $user?->cliente?->direccion) }}" 
                           id="direccion" placeholder="{{ __('Ingrese su dirección completa') }}">
                    {!! $errors->first('direccion', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>

            <div class="col-md-6">
                <!-- Nombre del Conjunto/Cerrado -->
                <div class="form-group mb-3">
                    <label for="nombre_conjunto_cerrado" class="form-label">
                        {{ __('Nombre del Conjunto/Cerrado') }}
                        <small class="text-muted">({{ __('Opcional') }})</small>
                    </label>
                    <input type="text" name="nombre_conjunto_cerrado" class="form-control @error('nombre_conjunto_cerrado') is-invalid @enderror" 
                           value="{{ old('nombre_conjunto_cerrado', $user?->cliente?->nombre_conjunto_cerrado) }}" 
                           id="nombre_conjunto_cerrado" placeholder="{{ __('Ej: Conjunto Residencial Los Pinos') }}">
                    {!! $errors->first('nombre_conjunto_cerrado', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>

            <div class="col-md-6">
                <!-- Interior/Apartamento -->
                <div class="form-group mb-3">
                    <label for="interior_apartamento" class="form-label">
                        {{ __('Interior/Apartamento') }}
                        <small class="text-muted">({{ __('Opcional') }})</small>
                    </label>
                    <input type="text" name="interior_apartamento" class="form-control @error('interior_apartamento') is-invalid @enderror" 
                           value="{{ old('interior_apartamento', $user?->cliente?->interior_apartamento) }}" 
                           id="interior_apartamento" placeholder="{{ __('Ej: Apto 101, Interior 5, Torre 2') }}">
                    {!! $errors->first('interior_apartamento', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>

            <div class="col-md-6">
                <!-- Ciudad -->
                <div class="form-group mb-3">
                    <label for="ciudad_id" class="form-label">{{ __('Ciudad') }}</label>
                    <select name="ciudad_id" class="form-select @error('ciudad_id') is-invalid @enderror" id="ciudad_id">
                        <option value="">{{ __('Seleccione una ciudad') }}</option>
                        @foreach($ciudades as $ciudad)
                            <option value="{{ $ciudad->id_municipio }}" 
                                {{ $ciudadSeleccionada == $ciudad->id_municipio ? 'selected' : '' }}>
                                {{ $ciudad->municipio }}
                            </option>
                        @endforeach
                    </select>
                    {!! $errors->first('ciudad_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>

            <div class="col-md-6">
                <!-- Barrio -->
                <div class="form-group mb-3">
                    <label for="barrio_id" class="form-label">{{ __('Barrio') }}</label>
                    <select name="barrio_id" class="form-select @error('barrio_id') is-invalid @enderror" id="barrio_id">
                        <option value="">{{ __('Seleccione un barrio') }}</option>
                        @foreach($barrios as $barrio)
                            <option value="{{ $barrio->id }}" 
                                {{ old('barrio_id', $user?->cliente?->barrio_id) == $barrio->id ? 'selected' : '' }}>
                                {{ $barrio->nombre }}
                            </option>
                        @endforeach
                    </select>
                    {!! $errors->first('barrio_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    <small class="form-text text-muted">{{ __('Solo se muestran barrios de la localidad de Engativá, Bogotá') }}</small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Cargar barrios dinámicamente cuando se selecciona una ciudad
    // Solo mostrar barrios de Engativá
    document.addEventListener('DOMContentLoaded', function() {
        const ciudadSelect = document.getElementById('ciudad_id');
        const barrioSelect = document.getElementById('barrio_id');
        
        // Función para cargar barrios de Engativá
        function cargarBarriosEngativa() {
            barrioSelect.innerHTML = '<option value="">{{ __('Seleccione un barrio') }}</option>';
            
            // Cargar solo barrios de Engativá desde la API
            const url = '{{ route("barrios.engativa") }}';
            
            fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data && Array.isArray(data) && data.length > 0) {
                        data.forEach(barrio => {
                            const option = document.createElement('option');
                            option.value = barrio.id;
                            option.textContent = barrio.nombre;
                            
                            // Si hay un barrio seleccionado previamente, marcarlo
                            @if(isset($user) && $user->cliente && $user->cliente->barrio_id)
                                if (barrio.id == {{ $user->cliente->barrio_id }}) {
                                    option.selected = true;
                                }
                            @endif
                            
                            barrioSelect.appendChild(option);
                        });
                    } else {
                        console.warn('No se encontraron barrios de Engativá');
                        barrioSelect.innerHTML = '<option value="">{{ __('No hay barrios disponibles') }}</option>';
                    }
                })
                .catch(error => {
                    console.error('Error al cargar barrios de Engativá:', error);
                    barrioSelect.innerHTML = '<option value="">{{ __('Error al cargar barrios') }}</option>';
                });
        }
        
        // Cargar barrios al cambiar la ciudad (solo si es Bogotá)
        if (ciudadSelect) {
            ciudadSelect.addEventListener('change', function() {
                const ciudadId = this.value;
                const ciudadNombre = this.options[this.selectedIndex].text;
                
                // Solo cargar barrios si es Bogotá o está vacío
                if (!ciudadId || ciudadNombre.toLowerCase().includes('bogotá') || ciudadNombre.toLowerCase().includes('bogota')) {
                    cargarBarriosEngativa();
                } else {
                    // Si no es Bogotá, limpiar barrios
                    barrioSelect.innerHTML = '<option value="">{{ __('Seleccione un barrio') }}</option>';
                    barrioSelect.innerHTML += '<option value="" disabled>{{ __('Solo se muestran barrios de Engativá, Bogotá') }}</option>';
                }
            });
            
            // Cargar barrios al inicio si Bogotá está seleccionada por defecto
            const ciudadSeleccionada = ciudadSelect.value;
            const ciudadNombre = ciudadSelect.options[ciudadSelect.selectedIndex]?.text || '';
            
            if (ciudadSeleccionada && (ciudadNombre.toLowerCase().includes('bogotá') || ciudadNombre.toLowerCase().includes('bogota'))) {
                cargarBarriosEngativa();
            } else if (ciudadSeleccionada) {
                // Si hay una ciudad seleccionada pero no es Bogotá, cargar barrios de todos modos
                cargarBarriosEngativa();
            }
        }
    });
</script>

<!-- Sección: Datos de Cuenta -->
@php
    // Verificar si el usuario tiene cuentas sociales vinculadas
    $hasSocialAccounts = isset($user) && $user->hasSocialAccounts();
    $isOAuthOnly = isset($user) && $user->isOAuthOnly();
    // Mostrar sección de contraseña:
    // 1. Si es un usuario nuevo (registro normal) - SIEMPRE mostrar
    // 2. Si es un usuario existente que NO se registró por OAuth - mostrar normalmente
    // 3. Si es un usuario existente que SÍ se registró por OAuth - mostrar como OPCIONAL para agregar contraseña local
    $showPasswordSection = true; // Siempre mostrar, pero hacer opcional si es OAuth
@endphp

@if($showPasswordSection)
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">
            {{ __('Datos de Cuenta') }}
            @if($isOAuthOnly)
                <small class="text-muted">({{ __('Opcional: Agregar contraseña local') }})</small>
            @endif
        </h5>
    </div>
    <div class="card-body">
        @if($isOAuthOnly)
            <div class="alert alert-info mb-3">
                <i class="fas fa-info-circle"></i> 
                <strong>{{ __('Registro por Redes Sociales') }}</strong>
                <p class="mb-0 mt-2">
                    {{ __('Te registraste usando una cuenta de red social (Google, Facebook, etc.).') }}
                    <br>
                    <strong>{{ __('Importante:') }}</strong> {{ __('Tu contraseña de Google/Facebook NO funciona aquí. La aplicación generó una contraseña automática que no puedes usar directamente.') }}
                    <br>
                    {{ __('Si deseas iniciar sesión también con email y contraseña (además del botón de Google), puedes crear una contraseña local aquí.') }}
                </p>
            </div>
        @elseif($hasSocialAccounts)
            <div class="alert alert-info mb-3">
                <i class="fas fa-info-circle"></i> 
                {{ __('Tienes una cuenta vinculada con redes sociales. Puedes cambiar tu contraseña local aquí.') }}
            </div>
        @endif
        
        <div class="row">
            <div class="col-md-6">
                <!-- Contraseña -->
                <div class="form-group mb-3">
                    <label for="password" class="form-label">
                        {{ __('Contraseña') }}
                        @if($isOAuthOnly)
                            <span class="text-muted">({{ __('Opcional') }})</span>
                        @elseif(isset($user))
                            <span class="text-muted">({{ __('Deja en blanco para no cambiar') }})</span>
                        @else
                            <span class="text-danger">*</span>
                        @endif
                    </label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                        </div>
                        <input type="password" name="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               id="password" 
                               placeholder="{{ $isOAuthOnly ? __('Opcional: Ingrese una contraseña local') : __('Ingrese una contraseña') }}"
                               @if($isOAuthOnly || isset($user)) @else required @endif>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-outline-secondary toggle-password" data-target="#password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    {!! $errors->first('password', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    @if($isOAuthOnly)
                        <small class="form-text text-muted">{{ __('Opcional: Agrega una contraseña para iniciar sesión con email y contraseña') }}</small>
                    @elseif(isset($user))
                        <small class="form-text text-muted">{{ __('Deja en blanco si no deseas cambiar la contraseña') }}</small>
                    @endif
                </div>
            </div>

            <div class="col-md-6">
                <!-- Confirmar Contraseña -->
                <div class="form-group mb-3">
                    <label for="password_confirmation" class="form-label">
                        {{ __('Confirmar Contraseña') }}
                        @if($isOAuthOnly)
                            <span class="text-muted">({{ __('Opcional') }})</span>
                        @elseif(isset($user))
                            <span class="text-muted">({{ __('Solo si cambias la contraseña') }})</span>
                        @else
                            <span class="text-danger">*</span>
                        @endif
                    </label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                        </div>
                        <input type="password" name="password_confirmation" 
                               class="form-control @error('password_confirmation') is-invalid @enderror" 
                               id="password_confirmation" 
                               placeholder="{{ __('Confirme la contraseña') }}"
                               @if($isOAuthOnly || isset($user)) @else required @endif>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-outline-secondary toggle-password" data-target="#password_confirmation">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    {!! $errors->first('password_confirmation', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Sección: Foto de Perfil -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">{{ __('Foto de Perfil') }}</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <!-- Foto de Perfil -->
                <div class="form-group mb-3">
                    <label for="avatar" class="form-label">{{ __('Foto de Perfil') }}</label>
                    <div class="input-group">
                        <input type="file" name="avatar" class="form-control @error('avatar') is-invalid @enderror" id="avatar" accept="image/*" onchange="previewImage(this)">
                        <label class="input-group-text" for="avatar">
                            <i class="fas fa-image"></i> {{ __('Seleccionar Imagen') }}
                        </label>
                    </div>
                    <div id="imagePreview" class="mt-3"></div>
                    {!! $errors->first('avatar', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    @if($user?->avatar)
                        <div class="mt-3">
                            @php
                                // La imagen se guarda en public/storage/img/avatar/filename.png
                                // La ruta en BD es: storage/img/avatar/filename.png
                                $avatarPath = null;
                                if (strpos($user->avatar, 'storage/img/avatar/') === 0) {
                                    // Verificar si el archivo existe físicamente
                                    $filePath = public_path($user->avatar);
                                    if (file_exists($filePath)) {
                                        // Ruta nueva: public/storage/img/avatar/filename.png
                                        $avatarPath = asset('public/' . $user->avatar);
                                    } else {
                                        // Intentar con solo el nombre del archivo
                                        $fileName = basename($user->avatar);
                                        $altPath = public_path('storage/img/avatar/' . $fileName);
                                        if (file_exists($altPath)) {
                                            $avatarPath = asset('public/storage/img/avatar/' . $fileName);
                                        }
                                    }
                                } elseif (file_exists(public_path('storage/' . $user->avatar))) {
                                    // Ruta antigua en storage
                                    $avatarPath = asset('storage/' . $user->avatar);
                                } elseif (file_exists(public_path($user->avatar))) {
                                    // Ruta absoluta
                                    $avatarPath = asset('public/' . $user->avatar);
                                } elseif (\Illuminate\Support\Facades\Storage::disk('public')->exists($user->avatar)) {
                                    // Ruta en storage disk
                                    $avatarPath = \Illuminate\Support\Facades\Storage::disk('public')->url($user->avatar);
                                }
                            @endphp
                            @if($avatarPath)
                                <img src="{{ $avatarPath }}" alt="Foto de Perfil" class="img-thumbnail" style="max-width: 150px; height: 150px; object-fit: cover; border-radius: 50%;">
                                <p class="text-muted small mt-2">{{ __('Foto actual') }}</p>
                            @endif
                        </div>
                    @endif
                    <small class="form-text text-muted">{{ __('Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 2MB') }}</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Botones de acción -->
<div class="row">
    <div class="col-md-6">
        <a href="{{ route('users.index') }}" class="btn btn-secondary w-100">{{ __('Volver ') }}</a>
    </div>
    <div class="col-md-6">
        <button type="submit" class="btn btn-primary w-100">{{ __('Enviar') }}</button>
    </div>
</div>

<script>
    // Función para previsualizar la imagen seleccionada
    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `
                    <div class="text-center">
                        <img src="${e.target.result}" alt="Vista previa" 
                             class="img-thumbnail" 
                             style="max-width: 150px; height: 150px; object-fit: cover; border-radius: 50%;">
                        <p class="text-muted small mt-2">{{ __('Vista previa de la nueva imagen') }}</p>
                    </div>
                `;
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.innerHTML = '';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const togglePasswordButtons = document.querySelectorAll('.toggle-password');

        togglePasswordButtons.forEach(button => {
            button.addEventListener('click', function () {
                const target = document.querySelector(this.getAttribute('data-target'));
                const icon = this.querySelector('i');

                if (target.type === 'password') {
                    target.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    target.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });
    });
</script>
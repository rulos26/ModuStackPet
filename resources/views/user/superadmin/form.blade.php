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
                <!-- Correo Electrónico -->
                <div class="form-group mb-3">
                    <label for="email" class="form-label">{{ __('Correo Electrónico') }}</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user?->email) }}" id="email" placeholder="Correo Electrónico">
                    {!! $errors->first('email', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>

            <div class="col-md-6">
                <!-- Foto de Perfil -->
                <div class="form-group mb-3">
                    <label for="avatar" class="form-label">{{ __('Foto de Perfil') }}</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" name="avatar" class="custom-file-input @error('avatar') is-invalid @enderror" id="avatar" accept="image/*">
                            <label class="custom-file-label" for="avatar">{{ __('Seleccionar Imagen') }}</label>
                        </div>
                    </div>
                    {!! $errors->first('avatar', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                    @if($user?->avatar)
                        <div class="mt-3">
                            <img src="{{ asset('public/' . $user->avatar) }}" alt="Foto de Perfil" class="img-thumbnail" style="max-width: 150px;">
                        </div>
                    @endif
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

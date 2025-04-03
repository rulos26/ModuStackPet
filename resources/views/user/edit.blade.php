@extends('adminlte::page')

@section('title', 'Editar Perfil')

@section('content_header')
    <h1>Editar Perfil</h1>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Actualizar Información</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('users.update',$user) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Foto de Perfil --}}
                    <div class="text-center mb-4">
                        <img id="preview-image" 
                             src="{{ $user->profile_photo_url ?? asset('images/default-avatar.png') }}" 
                             class="rounded-circle img-thumbnail" 
                             style="width: 120px; height: 120px;" />
                             <img id="preview-image" src="{{ asset('storage/' . $user->profile_photo_path) ?? asset('images/default-avatar.png') }}" class="rounded-circle img-thumbnail" style="width: 120px; height: 120px;" />

                        <div class="mt-2">
                            <label class="btn btn-outline-primary btn-sm">
                                Cambiar Imagen <input type="file" name="profile_photo_path" id="profile_photo" class="d-none">
                            </label>
                        </div>
                    </div>

                    {{-- Nombre --}}
                    <div class="form-group">
                        <label for="name">Nombre</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ Auth::user()->name }}" required>
                    </div>

                    {{-- Email --}}
                    <div class="form-group">
                        <label for="email">Correo Electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}" required>
                    </div>

                    {{-- Contraseña --}}
                    <div class="form-group">
                        <label for="password">Nueva Contraseña <small>(Opcional)</small></label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>

                    {{-- Botón de Guardar --}}
                    <div class="text-right">
                        <button type="submit" class="btn btn-success">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Previsualizar la imagen antes de subirla --}}
@section('js')
<script>
    document.getElementById('profile_photo').addEventListener('change', function(event) {
        let reader = new FileReader();
        reader.onload = function(){
            let output = document.getElementById('preview-image');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    });
</script>
@endsection

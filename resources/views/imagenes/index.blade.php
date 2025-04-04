@extends('adminlte::page') 

@section('template_title')
    Hechos Ocurrencias
@endsection

@section('content')
<div class="container">
    <h2>Subir Imágenes</h2>
    <form action="{{ route('imagenes.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="cedula_numero">Cédula</label>
            <input type="text" name="cedula_numero" class="form-control">
        </div>
        <div class="mb-3">
            <label for="tipo_imagen">Tipo de Imagen</label>
            <select name="tipo_imagen" class="form-control">
                <option value="reclamante_general">Reclamante General</option>
                <option value="medio_cuerpo">Medio Cuerpo</option>
                <option value="grupo_familiar">Grupo Familiar</option>
                <option value="nomenclatura">Nomenclatura</option>
                <option value="fachada">Fachada</option>
                <option value="cedula">Cédula del Reclamante</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="imagen">Imagen</label>
            <input type="file" name="imagen" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Subir</button>
    </form>

    <h3 class="mt-4">Imágenes Subidas</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Cédula</th>
                <th>Tipo</th>
                <th>Imagen</th>
            </tr>
        </thead>
        <tbody>
            @foreach($imagenes as $imagen)
                <tr>
                    <td>{{ $imagen->cedula_numero }}</td>
                    <td>{{ $imagen->tipo_imagen }}</td>
                    <td><img src="{{ asset('storage/'.$imagen->ruta_imagen) }}" width="100"></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
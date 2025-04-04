@extends('adminlte::page')

@section('title', 'Carta de Autorización de Datos Personales')

@section('content_header')
    <h1>Carta de Autorización de Datos Personales</h1>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('autorizacion.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="cedula">Cédula</label>
            <input type="text" name="cedula" class="form-control @error('cedula') is-invalid @enderror" value="{{ old('cedula') }}">
            @error('cedula')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}">
            @error('nombre')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="apellido">Apellido</label>
            <input type="text" name="apellido" class="form-control @error('apellido') is-invalid @enderror" value="{{ old('apellido') }}">
            @error('apellido')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="foto_cedula">Foto de la Cédula</label>
            <input type="file" name="foto_cedula" class="form-control-file @error('foto_cedula') is-invalid @enderror">
            @error('foto_cedula')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="foto_firma">Foto de la Firma</label>
            <input type="file" name="foto_firma" class="form-control-file @error('foto_firma') is-invalid @enderror">
            @error('foto_firma')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Enviar</button>
    </form>
@stop

@extends('layouts.app')

@section('template_title')
    {{ $mensajeDeBienvenida->name ?? 'Mostrar Mensaje de Bienvenida' }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <!-- Tarjeta para mostrar los detalles del mensaje de bienvenida -->
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <!-- Título de la tarjeta -->
                        <div class="float-left">
                            <span class="card-title">Detalles del Mensaje de Bienvenida</span>
                        </div>
                        <!-- Botón para regresar al índice -->
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('mensaje-de-bienvenidas.index') }}"> Volver</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        <!-- Mostrar el título del mensaje -->
                        <div class="form-group mb-2 mb20">
                            <strong>Título:</strong>
                            {{ $mensajeDeBienvenida->titulo }}
                        </div>
                        <!-- Mostrar la descripción del mensaje -->
                        <div class="form-group mb-2 mb20">
                            <strong>Descripción:</strong>
                            {{ $mensajeDeBienvenida->descripcion }}
                        </div>
                        <!-- Mostrar el logo del mensaje -->
                        <div class="form-group mb-2 mb20">
                            <strong>Logo:</strong>
                            @if ($mensajeDeBienvenida->logo)
                                <img src="{{ asset('storage/' . $mensajeDeBienvenida->logo) }}" alt="Logo" style="width: 100px; height: 100px; object-fit: cover;">
                            @else
                                Sin logo
                            @endif
                        </div>
                        <!-- Mostrar el rol asociado al mensaje -->
                        <div class="form-group mb-2 mb20">
                            <strong>Rol:</strong>
                            {{ $mensajeDeBienvenida->rol }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

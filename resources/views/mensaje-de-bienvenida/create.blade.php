@extends('layouts.app')

@section('template_title')
    Crear Mensaje de Bienvenida
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <!-- Tarjeta para el formulario de creación -->
                <div class="card card-default">
                    <div class="card-header">
                        <!-- Título de la tarjeta -->
                        <span class="card-title">Crear Mensaje de Bienvenida</span>
                    </div>
                    <div class="card-body bg-white">
                        <!-- Formulario para crear un nuevo mensaje de bienvenida -->
                        <form method="POST" action="{{ route('mensaje-de-bienvenidas.store') }}" role="form" enctype="multipart/form-data">
                            @csrf

                            <!-- Incluir el formulario reutilizable -->
                            @include('mensaje-de-bienvenida.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

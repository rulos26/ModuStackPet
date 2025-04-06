@extends('layouts.app')

@section('template_title')
    Editar Mensaje de Bienvenida
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <!-- Tarjeta para el formulario de edición -->
                <div class="card card-default">
                    <div class="card-header">
                        <!-- Título de la tarjeta -->
                        <span class="card-title">Editar Mensaje de Bienvenida</span>
                    </div>
                    <div class="card-body bg-white">
                        <!-- Formulario para editar un mensaje de bienvenida -->
                        <form method="POST" action="{{ route('mensaje-de-bienvenidas.update', $mensajeDeBienvenida->id) }}" role="form" enctype="multipart/form-data">
                            <!-- Método PATCH para actualizar -->
                            {{ method_field('PATCH') }}
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

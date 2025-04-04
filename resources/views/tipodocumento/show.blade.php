@extends('adminlte::page') @section('content_header')
    <h1 class="m-0 text-dark"> </h1>
    @stop


    @section('content')
        <section class="content container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="float-left">
                                <span id="card_title">
                                    <i class="fas fa-solid fa-business-time"> Tipos jornadas Laborales</i>
                                </span>
                            </div>
                            <div class="float-right">
                                <a class="btn btn-primary" href="{{ route('tipodocumento.index') }}">
                                    {{ __('Volver') }}</a>
                            </div>
                        </div>

                        <div class="card-body">

                            <div class="form-group">
                                <strong>Nombre:</strong>
                                {{ $tipoDocumentos->nombre }}
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endsection
    @section('footer')
        <!-- Contenido del footer para la página de inicio -->

    @endsection

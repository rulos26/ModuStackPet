@extends('adminlte::page') @section('content_header')
    <h1 class="m-0 text-dark"</h1>
    @stop


    @section('content')
        <section class="content container-fluid">
            <div class="">
                <div class="col-md-12">

                    @includeif('partials.errors')

                    <div class="card card-default">
                        <div class="card-header">
                            <span id="card_title">
                                <i class="fas fa-solid fa-business-time"> Actualizar Tipo jornada Laboral</i>
                            </span>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('tipodocumento.update', $dataTipoJornada->id) }}"
                                role="form" enctype="multipart/form-data">
                                {{ method_field('PATCH') }}
                                @csrf

                                @include('AdminDashboard.data-tipo-jornada.form')

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endsection
    @section('footer')
        <!-- Contenido del footer para la página de inicio -->

    @endsection

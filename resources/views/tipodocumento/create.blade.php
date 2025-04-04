@extends('adminlte::page')

@section('content_header')
    <h1 class="m-0 text-dark"</h1>
    @stop

    @section('content')
        <section class="content container-fluid">
            <div class="row">
                <div class="col-md-12">

                    @includeif('partials.errors')

                    <div class="card card-default">
                        <div class="card-header">
                            <span id="card_title">
                                <i class="fas fa-solid fa-business-time"> Crear Tipo Documentos</i>
                            </span>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('tipodocumento.store') }}" role="form"
                                enctype="multipart/form-data">
                                @csrf

                             @include('AdminDashboard.tipodocumento.form')

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

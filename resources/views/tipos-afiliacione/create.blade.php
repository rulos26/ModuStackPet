@extends('adminlte::page')

@section('template_title')
    Tipos Afiliacione
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Tipos Afiliacione</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('tipos-afiliaciones.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('tipos-afiliacione.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

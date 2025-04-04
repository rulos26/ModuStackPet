@extends('adminlte::page')

@section('template_title')
    Datos Basicos Hijo
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Datos Basicos Hijo</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('datos-basicos-hijos.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('datos-basicos-hijo.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

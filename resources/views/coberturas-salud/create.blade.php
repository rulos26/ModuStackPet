@extends('adminlte::page')

@section('template_title')
    Coberturas Salud
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Coberturas Salud</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('coberturas-saluds.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('coberturas-salud.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

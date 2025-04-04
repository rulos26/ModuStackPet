@extends('adminlte::page') 

@section('template_title')
    Datos Basico del Afiliado
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Datos Basico del Afiliado</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('datos-basicos.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('datos-basico.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

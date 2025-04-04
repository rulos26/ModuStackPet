@extends('adminlte::page') 

@section('template_title')
Datos Complementarios del Afiliado
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Datos Complementarios del Afiliado</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('afiliados.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('afiliado.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@extends('adminlte::page')

@section('template_title')
    Tipo Documento
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Tipo Documento</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('tipo-documentos.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('tipo-documento.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

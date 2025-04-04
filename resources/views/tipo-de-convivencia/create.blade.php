@extends('adminlte::page') 

@section('template_title')
    Tipo De Convivencia
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Tipo De Convivencia</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('tipo-de-convivencias.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('tipo-de-convivencia.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

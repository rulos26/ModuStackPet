@extends('adminlte::page') 

@section('template_title')
    Hechos Ocurrencia
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Hechos Ocurrencia</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('hechos-ocurrencias.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('hechos-ocurrencia.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

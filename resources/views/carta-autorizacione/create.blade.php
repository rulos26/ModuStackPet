@extends('adminlte::page')
  
@section('template_title')
    Carta Autorizacione
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Carta Autorizacione</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('carta-autorizaciones.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('carta-autorizacione.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

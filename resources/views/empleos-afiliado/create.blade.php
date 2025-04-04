@extends('adminlte::page')

@section('template_title')
    Empleos Afiliado
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Empleos Afiliado</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('empleos-afiliados.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('empleos-afiliado.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@extends('adminlte::page')

@section('template_title')
    Informe
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Informe</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('informes.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('informe.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@include('sidebar.right-sidebar')

@extends('adminlte::page') 

@section('template_title')
    Reclamantes Afiliado
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Reclamantes Afiliado</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('reclamantes-afiliados.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('reclamantes-afiliado.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

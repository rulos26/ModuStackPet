@extends('adminlte::page') 

@section('template_title')
    Muertes Origen
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Muertes Origen</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('muertes-origens.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('muertes-origen.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

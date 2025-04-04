@extends('adminlte::page')

@section('template_title')
    Personas Afiliada
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Personas Afiliada</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('personas-afiliadas.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('personas-afiliada.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

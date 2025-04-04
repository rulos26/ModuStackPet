@extends('adminlte::page') 

@section('template_title')
    Conclusione
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Conclusione</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('conclusiones.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('conclusione.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

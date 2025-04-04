@extends('adminlte::page') 

@section('template_title')
    Amparo
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Amparo</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('amparos.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('amparo.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
